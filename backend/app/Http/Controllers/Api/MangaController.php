<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MangaController extends Controller
{
    /**
     * 📄 Listar mangas con filtros
     */
    public function index(Request $request): JsonResponse
    {
        $query = Manga::query()
            ->with([
                'genres', 
                'tags', 
                'titles',
            ])
            ->withCount([
                'favorites', 
                'views', 
                'comments'
            ]);

        // 🔍 Buscar por título o títulos alternativos
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('titles', function ($q2) use ($search) {
                      $q2->where('normalized_title', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 🎭 Filtro por género (slug)
        if ($request->filled('genre')) {
            $genre = $request->genre;

            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('slug', $genre);
            });
        }

        // 🏷️ Filtro por tag (slug)
        if ($request->filled('tag')) {
            $tag = $request->tag;

            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }

        // 📊 Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📚 Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 🔥 Orden
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderByDesc('views_count');
                    break;
                case 'favorites':
                    $query->orderByDesc('favorites_count');
                    break;
                case 'latest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $mangas = $query->paginate(12);

        return response()->json([
            'data' => $mangas
        ]);
    }

    /**
     * 🔍 Ver manga por slug
     */
    public function show($slug): JsonResponse
    {
        $manga = Manga::where('slug', $slug)
            ->with([
                'titles',
                'genres',
                'tags',
                'chapters.versions.language',
                'chapters.versions.scanGroup'
            ])
            ->withCount(['favorites', 'views', 'comments'])
            ->first();

        if (!$manga) {
            return response()->json([
                'message' => 'Manga not found'
            ], 404);
        }

        return response()->json([
            'data' => $manga
        ]);
    }

    /**
     * 📥 Crear manga
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasRole(Role::ADMIN) &&
            !$user->hasRole(Role::UPLOADER)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'type' => 'required|string',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        return DB::transaction(function () use ($request, $user) {

            // 🔥 Normalización PRO
            $normalized = Str::of($request->title)->lower()->trim()->__toString();

            // ❗ Evitar duplicados
            $exists = Manga::where('normalized_title', $normalized)
                ->orWhereHas('titles', fn($q) => $q->where('normalized_title', $normalized))
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Manga already exists'
                ], 422);
            }

            // 🔥 Slug único
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;

            while (Manga::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            // 📚 Crear manga
            $manga = Manga::create([
                'title' => $request->title,
                'normalized_title' => $normalized,
                'slug' => $slug,
                'description' => $request->description,
                'status' => $request->status,
                'type' => $request->type,
                'created_by' => $user->id
            ]);

            // 🔥 título principal
            $manga->titles()->create([
                'title' => $request->title,
                'normalized_title' => $normalized,
                'type' => 'main'
            ]);

            // 🎭 géneros
            if ($request->filled('genres')) {
                $manga->genres()->sync($request->genres);
            }

            // 🏷️ tags
            if ($request->filled('tags')) {
                $manga->tags()->sync($request->tags);
            }

            // 🖼️ cover
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = "mangas/{$manga->id}/cover";

                $storedPath = $file->storeAs($path, $filename, 'public');

                $manga->update([
                    'cover_image' => $storedPath
                ]);
            }

            return response()->json([
                'message' => 'Manga created',
                'data' => $manga->load(['genres', 'tags', 'titles'])
            ], JsonResponse::HTTP_CREATED);
        });
    }

    /**
     * ✏️ Actualizar manga
     */
    public function update(Request $request, $id): JsonResponse
    {
        $manga = Manga::find($id);

        if (!$manga) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $user = $request->user();

        if (
            $manga->created_by !== $user->id &&
            !$user->hasRole(Role::SUPER_ADMIN)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'sometimes|string',
            'type' => 'sometimes|string',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        return DB::transaction(function () use ($request, $manga) {

            // 🔥 Si cambia título
            if ($request->filled('title')) {

                $normalized = Str::of($request->title)->lower()->trim()->__toString();

                // ❗ evitar duplicado
                $exists = Manga::where('normalized_title', $normalized)
                    ->where('id', '!=', $manga->id)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'message' => 'Manga with this title already exists'
                    ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }

                $manga->update([
                    'title' => $request->title,
                    'normalized_title' => $normalized
                ]);

                // 🔥 sync título principal
                $manga->titles()->updateOrCreate(
                    ['type' => 'main'],
                    [
                        'title' => $request->title,
                        'normalized_title' => $normalized
                    ]
                );
            }

            // 🔄 otros campos
            $manga->update($request->only([
                'description',
                'status',
                'type'
            ]));

            // 🎭 géneros
            if ($request->filled('genres')) {
                $manga->genres()->sync($request->genres);
            }

            // 🏷️ tags
            if ($request->filled('tags')) {
                $manga->tags()->sync($request->tags);
            }

            // 🖼️ cover
            if ($request->hasFile('cover_image')) {

                if ($manga->cover_image) {
                    Storage::disk('public')->delete($manga->cover_image);
                }

                $file = $request->file('cover_image');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = "mangas/{$manga->id}/cover";

                $storedPath = $file->storeAs($path, $filename, 'public');

                $manga->update([
                    'cover_image' => $storedPath
                ]);
            }

            return response()->json([
                'message' => 'Manga updated',
                'data' => $manga->load(['genres', 'tags', 'titles'])
            ], JsonResponse::HTTP_OK);
        });
    }

    /**
     * ❌ Eliminar manga
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $manga = Manga::find($id);

        if (!$manga) {
            return response()->json(['message' => 'Not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $user = $request->user();

        if (
            $manga->created_by !== $user->id &&
            !$user->hasRole(Role::SUPER_ADMIN)
        ) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $manga->delete();

        return response()->json([
            'message' => 'Manga deleted'
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}
