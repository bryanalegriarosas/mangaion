<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterVersionResource;
use App\Models\Chapter;
use App\Models\ChapterVersion;
use App\Models\Language;
use App\Models\Manga;
use App\Models\Page;
use App\Models\Role;
use App\Models\ScanGroup;
use App\Models\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function __construct(private Helpers $helper) {}

    // ─────────────────────────────────────────────────────────────
    // 📖 LISTAR capítulos de un manga — route model binding por ID
    // ─────────────────────────────────────────────────────────────
    public function index(Manga $manga): JsonResponse
    {
        $chapters = $manga->chapters()
            ->with([
                'versions.language:id,name,code',
                'versions.scanGroup:id,name',
                'versions.pages',
            ])
            ->orderBy('chapter_number', 'desc')
            ->get();

        return response()->json(['data' => $chapters]);
    }

    // ─────────────────────────────────────────────────────────────
    // 📚 MIS VERSIONES — versiones del usuario autenticado
    //    Combina: mangas creados por el usuario + grupos de scan
    // ─────────────────────────────────────────────────────────────
    public function myVersions(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $versions = ChapterVersion::where(function ($q) use ($user) {
            // Opción A: versiones de mangas que el usuario creó
            $q->whereHas(
                'chapter.manga',
                fn($q2) =>
                $q2->where('created_by', $user->id)
            )
                // Opción B: versiones de scan groups donde el usuario es miembro
                ->orWhereHas(
                    'scanGroup.users',
                    fn($q2) =>
                    $q2->where('users.id', $user->id)
                );
        })
            ->with([
                'chapter:id,manga_id,chapter_number,volume_number',
                'chapter.manga:id,title,slug,cover_image',
                'language:id,name,code',
                'scanGroup:id,name',
            ])
            ->withCount('pages')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => ChapterVersionResource::collection($versions)->resolve(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // 📥 CREAR capítulo base
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request, Manga $manga): JsonResponse
    {
        // Autorización — solo admins o uploaders globales
        if (
            !$request->user()->hasRole(Role::ADMIN) &&
            !$request->user()->hasRole(Role::SUPER_ADMIN) &&
            !$request->user()->hasRole(Role::UPLOADER)
        ) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'chapter_number' => ['required', 'numeric'],
            'volume_number'  => ['nullable', 'integer'],
        ]);

        $exists = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', $request->chapter_number)
            ->exists();

        if ($exists) {
            return response()->json(
                ['message' => 'Chapter already exists'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $chapter = Chapter::create([
            'manga_id'       => $manga->id,
            'chapter_number' => $request->chapter_number,
            'volume_number'  => $request->volume_number,
        ]);

        return response()->json(
            ['message' => 'Chapter created', 'data' => $chapter],
            JsonResponse::HTTP_CREATED
        );
    }

    // ─────────────────────────────────────────────────────────────
    // 🌍 CREAR versión de capítulo
    // ─────────────────────────────────────────────────────────────
    public function storeVersion(Request $request, Chapter $chapter): JsonResponse
    {
        $request->validate([
            'language_id'   => ['required', 'exists:languages,id'],
            'scan_group_id' => ['required', 'exists:scan_groups,id'],
            'title'         => ['nullable', 'string', 'max:255'],
            'published_at'  => ['nullable', 'date'],
        ]);

        if (!$request->user()->canUploadToScan($request->scan_group_id)) {
            return response()->json(
                ['message' => 'You do not have permission to upload to this scan group'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        $exists = ChapterVersion::where([
            'chapter_id'    => $chapter->id,
            'language_id'   => $request->language_id,
            'scan_group_id' => $request->scan_group_id,
        ])->exists();

        if ($exists) {
            return response()->json(
                ['message' => 'This version already exists'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $language  = Language::find($request->language_id);
        $scanGroup = ScanGroup::find($request->scan_group_id);

        $baseSlug = Str::slug(
            "{$scanGroup->name}-{$language->code}-cap-{$this->helper->formatNumber($chapter->chapter_number)}"
        );

        // Slug único
        $existing = ChapterVersion::where('slug', 'LIKE', "{$baseSlug}%")->count();
        $slug     = $existing > 0 ? "{$baseSlug}-" . ($existing + 1) : $baseSlug;

        $version = ChapterVersion::create([
            'chapter_id'    => $chapter->id,
            'language_id'   => $request->language_id,
            'scan_group_id' => $request->scan_group_id,
            'title'         => $request->title,
            'slug'          => $slug,
            'published_at'  => $request->published_at ?? now(),
        ]);

        return response()->json(
            ['message' => 'Version created', 'data' => $version],
            JsonResponse::HTTP_CREATED
        );
    }

    // ─────────────────────────────────────────────────────────────
    // 🖼️ SUBIR páginas
    // ─────────────────────────────────────────────────────────────
    public function storePages(Request $request, ChapterVersion $version): JsonResponse
    {
        if (!$request->user()->canUploadToScan($version->scan_group_id)) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'images'   => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $version) {
            $pageNumber = $version->pages()->max('page_number') ?? 0;
            $mangaId    = $version->chapter->manga->id;
            $basePath   = "mangas/{$mangaId}/chapters/{$version->chapter_id}/versions/{$version->id}";

            foreach ($request->file('images') as $image) {
                $pageNumber++;
                $filename = str_pad($pageNumber, 4, '0', STR_PAD_LEFT)
                    . '.' . $image->getClientOriginalExtension();

                $path = $image->storeAs($basePath, $filename, 'public');

                Page::create([
                    'chapter_version_id' => $version->id,
                    'page_number'        => $pageNumber,
                    'image_url'          => asset('storage/' . $path),
                ]);
            }
        });

        return response()->json(
            ['message' => 'Pages uploaded successfully'],
            JsonResponse::HTTP_CREATED
        );
    }

    // ─────────────────────────────────────────────────────────────
    // 🔄 REEMPLAZAR páginas — borra las anteriores con sus archivos
    // ─────────────────────────────────────────────────────────────
    public function replacePages(Request $request, ChapterVersion $version): JsonResponse
    {
        if (!$request->user()->canUploadToScan($version->scan_group_id)) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'images'   => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $version) {
            $mangaId  = $version->chapter->manga->id;
            $basePath = "mangas/{$mangaId}/chapters/{$version->chapter_id}/versions/{$version->id}";

            // ✅ Borrar archivos físicos antes de eliminar registros
            Storage::disk('public')->deleteDirectory($basePath);
            $version->pages()->delete();

            $pageNumber = 0;
            foreach ($request->file('images') as $image) {
                $pageNumber++;
                $filename = str_pad($pageNumber, 4, '0', STR_PAD_LEFT)
                    . '.' . $image->getClientOriginalExtension();

                $path = $image->storeAs($basePath, $filename, 'public');

                Page::create([
                    'chapter_version_id' => $version->id,
                    'page_number'        => $pageNumber,
                    'image_url'          => asset('storage/' . $path),
                ]);
            }
        });

        return response()->json(['message' => 'Pages replaced successfully']);
    }

    // ─────────────────────────────────────────────────────────────
    // 📚 READER — ver capítulo con navegación
    // ─────────────────────────────────────────────────────────────
    public function reader(Request $request, string $slug): JsonResponse
    {
        $version = ChapterVersion::where('slug', $slug)
            ->with([
                'chapter.manga',
                'language',
                'scanGroup',
                'pages' => fn($q) => $q->orderBy('page_number'),
            ])
            ->first();

        if (!$version) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $chapter = $version->chapter;
        $manga   = $chapter->manga;

        // Capítulo siguiente y anterior
        $nextChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        $prevChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        // Versión del mismo idioma si existe, si no la primera disponible
        $nextVersion = $nextChapter?->versions()
            ->where('language_id', $version->language_id)->first()
            ?? $nextChapter?->versions()->first();

        $prevVersion = $prevChapter?->versions()
            ->where('language_id', $version->language_id)->first()
            ?? $prevChapter?->versions()->first();

        // Registrar vista (una por IP y usuario)
        View::firstOrCreate([
            'manga_id'   => $manga->id,
            'ip_address' => $request->ip(),
            'user_id'    => $request->user()?->id,
        ]);

        return response()->json([
            'data' => [
                'manga'   => $manga,
                'chapter' => $chapter,
                'version' => $version,
                'pages'   => $version->pages,
                'navigation' => [
                    'next' => $nextVersion ? [
                        'chapter_number' => $nextChapter->chapter_number,
                        'slug'           => $nextVersion->slug,
                    ] : null,
                    'prev' => $prevVersion ? [
                        'chapter_number' => $prevChapter->chapter_number,
                        'slug'           => $prevVersion->slug,
                    ] : null,
                ],
            ],
        ]);
    }
}
