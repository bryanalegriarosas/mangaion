<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manga\StoreRequest;
use App\Http\Requests\Manga\UpdateRequest;
use App\Http\Resources\MangaResource;
use App\Models\Manga;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MangaController extends Controller
{

    public function __construct(private Helpers $helper) {}

    // ─────────────────────────────────────────────────────────────
    // 📄 LISTAR — con filtros y paginación
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Manga::query()
            ->with([
                'genres:id,name,slug',
                'tags:id,name,slug,category',
                'titles',
            ])
            ->withCount(['favorites', 'views', 'comments']);

        // 🔍 Búsqueda — usa normalized_title para consistencia con el esquema
        if ($request->filled('search')) {
            $search = Str::of($request->search)->lower()->trim()->__toString();

            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'LIKE', "%{$search}%")
                  ->orWhereHas('titles', fn($q2) =>
                      $q2->where('normalized_title', 'LIKE', "%{$search}%")
                  );
            });
        }

        // 🎭 Filtro por género (slug)
        if ($request->filled('genre')) {
            $query->whereHas('genres', fn($q) =>
                $q->where('slug', $request->genre)
            );
        }

        // 🏷️ Filtro por tag (slug)
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) =>
                $q->where('slug', $request->tag)
            );
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
        match ($request->input('sort', 'latest')) {
            'popular'   => $query->orderByDesc('views_count'),
            'favorites' => $query->orderByDesc('favorites_count'),
            default     => $query->latest(),
        };

        return MangaResource::collection($query->paginate(12));
    }

    // ─────────────────────────────────────────────────────────────
    // 🔍 VER — por ID
    // ─────────────────────────────────────────────────────────────
    public function show(Manga $manga): MangaResource
    {
        $manga->load([
            'titles',
            'genres:id,name,slug',
            'tags:id,name,slug,category',
            'chapters' => fn($q) => $q->orderBy('chapter_number'),
            'chapters.versions.language:id,name,code',
            'chapters.versions.scanGroup:id,name',
            'ratings:id,manga_id,rating',
        ])->loadCount(['favorites', 'views', 'comments']);

        return new MangaResource($manga);
    }

    // ─────────────────────────────────────────────────────────────
    // 📥 CREAR
    // ─────────────────────────────────────────────────────────────
    public function store(StoreRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user       = $request->user();
            $normalized = Str::of($request->title)->lower()->trim()->__toString();

            // ❗ Evitar duplicados por título normalizado
            $exists = Manga::where('normalized_title', $normalized)
                ->orWhereHas('titles', fn($q) =>
                    $q->where('normalized_title', $normalized)
                )
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A manga with this title already exists.',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            // 🔑 Slug único
            $slug = $this->helper->generateUniqueSlug(Str::slug($request->title));

            // 📚 Crear manga
            $manga = Manga::create([
                'title'            => $request->title,
                'normalized_title' => $normalized,
                'slug'             => $slug,
                'description'      => $request->description,
                'author'           => $request->author,
                'artist'           => $request->artist,
                'release_year'     => $request->release_year,
                'status'           => $request->status,
                'type'             => $request->type,
                'created_by'       => $user->id,
            ]);

            // 📝 Título principal en manga_titles
            $manga->titles()->create([
                'title'            => $request->title,
                'normalized_title' => $normalized,
                'type'             => 'main',
            ]);

            // 🎭 Géneros y tags
            if ($request->filled('genres')) {
                $manga->genres()->sync($request->genres);
            }
            if ($request->filled('tags')) {
                $manga->tags()->sync($request->tags);
            }

            // 🖼️ Cover
            $manga->update([
                'cover_image' => $this->storeCover($request, $manga->id),
            ]);

            return (new MangaResource(
                $manga->load(['genres', 'tags', 'titles'])
            ))->response()->setStatusCode(JsonResponse::HTTP_CREATED);
        });
    }

    // ─────────────────────────────────────────────────────────────
    // ✏️ ACTUALIZAR — por ID
    // ─────────────────────────────────────────────────────────────
    public function update(UpdateRequest $request, Manga $manga): JsonResponse
    {
        return DB::transaction(function () use ($request, $manga) {

            // 🔄 Si cambia el título
            if ($request->filled('title')) {
                $normalized = Str::of($request->title)->lower()->trim()->__toString();

                $duplicate = Manga::where('normalized_title', $normalized)
                    ->where('id', '!=', $manga->id)
                    ->exists();

                if ($duplicate) {
                    return response()->json([
                        'message' => 'A manga with this title already exists.',
                    ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }

                $manga->titles()->updateOrCreate(
                    ['type' => 'main'],
                    ['title' => $request->title, 'normalized_title' => $normalized]
                );

                $manga->fill([
                    'title'            => $request->title,
                    'normalized_title' => $normalized,
                ]);
            }

            // 🔄 Campos opcionales
            $manga->fill($request->only([
                'description',
                'author',
                'artist',
                'release_year',
                'status',
                'type',
            ]));

            $manga->save();

            // 🎭 Géneros y tags
            // Usamos has() en lugar de filled() para permitir arrays vacíos (desasignar todos)
            if ($request->has('genres')) {
                $manga->genres()->sync($request->genres ?? []);
            }
            if ($request->has('tags')) {
                $manga->tags()->sync($request->tags ?? []);
            }

            // 🖼️ Cover
            if ($request->hasFile('cover_image')) {
                if ($manga->cover_image) {
                    Storage::disk('public')->delete($manga->cover_image);
                }
                $manga->update([
                    'cover_image' => $this->storeCover($request, $manga->id),
                ]);
            }

            return (new MangaResource(
                $manga->load(['genres', 'tags', 'titles'])
            ))->response();
        });
    }

    // ─────────────────────────────────────────────────────────────
    // ❌ ELIMINAR — por ID
    // ─────────────────────────────────────────────────────────────
    public function destroy(Request $request, Manga $manga): JsonResponse
    {
        $user = $request->user();

        if (
            $manga->created_by !== $user->id &&
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasRole(Role::ADMIN)
        ) {
            return response()->json(
                ['message' => 'Unauthorized'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        // 🗑️ Borra todo el directorio del manga en storage (cover + imágenes futuras)
        Storage::disk('public')->deleteDirectory("mangas/{$manga->id}");

        $manga->delete();

        return response()->json(
            ['message' => 'Manga deleted successfully.'],
            JsonResponse::HTTP_OK
        );
    }

    private function storeCover(Request $request, int $mangaId): string
    {
        $file     = $request->file('cover_image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs("mangas/{$mangaId}/cover", $filename, 'public');
    }
}
