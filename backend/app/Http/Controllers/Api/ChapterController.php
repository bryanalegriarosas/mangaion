<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function __construct(private Helper $helper) {}

    /**
     * 📖 Listar capítulos de un manga
     */
    public function index($slug): JsonResponse
    {
        $manga = Manga::where('slug', $slug)->first();

        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapters = $manga->chapters()
            ->with([
                'versions.language',
                'versions.scanGroup',
                'versions.pages'
            ])
            ->orderBy('chapter_number', 'desc')
            ->get();

        return response()->json([
            'data' => $chapters
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 📥 Crear capítulo base
     */
    public function store(Request $request, Manga $manga): JsonResponse
    {
        // 🔐 Solo admins o uploaders globales
        if (
            !$request->user()->hasRole(Role::ADMIN) &&
            !$request->user()->hasRole(Role::SUPER_ADMIN) &&
            !$request->user()->hasRole(Role::UPLOADER)
        ) {
            return response()->json([
                'message' => 'Unauthorized'
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'chapter_number' => 'required|numeric',
            'volume_number' => 'nullable|integer'
        ]);

        $exists = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', $request->chapter_number)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Chapter already exists'
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $chapter = Chapter::create([
            'manga_id' => $manga->id,
            'chapter_number' => $request->chapter_number,
            'volume_number' => $request->volume_number
        ]);

        return response()->json([
            'message' => 'Chapter created',
            'data' => $chapter
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * 🌍 Crear versión de capítulo
     */
    public function storeVersion(Request $request, Chapter $chapter): JsonResponse
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'scan_group_id' => 'required|exists:scan_groups,id',
            'title' => 'nullable|string|max:255'
        ]);

        // 🔐 permiso real
        if (!$request->user()->canUploadToScan($request->scan_group_id)) {
            return response()->json([
                'message' => 'You do not have permission to upload to this scan group'
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $exists = ChapterVersion::where([
            'chapter_id' => $chapter->id,
            'language_id' => $request->language_id,
            'scan_group_id' => $request->scan_group_id
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This version already exists'
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $language = Language::find($request->language_id);
        $scanGroup = ScanGroup::find($request->scan_group_id);

        $baseSlug = "{$scanGroup->name}-{$language->code}-cap-{$this->helper->formatNumber($chapter->chapter_number)}";
        $slug = Str::slug($baseSlug);

        // 🔥 evitar duplicado de slug
        $count = ChapterVersion::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $version = ChapterVersion::create([
            'chapter_id' => $chapter->id,
            'language_id' => $request->language_id,
            'scan_group_id' => $request->scan_group_id,
            'title' => $request->title,
            'slug' => $slug
        ]);

        return response()->json([
            'message' => 'Version created',
            'data' => $version
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * 🖼️ Subir páginas (URLs)
     */
    public function storePages(Request $request, ChapterVersion $version): JsonResponse
    {
        $scanGroupId = $version->scan_group_id;

        if (!$request->user()->canUploadToScan($scanGroupId)) {
            return response()->json([
                'message' => 'You do not have permission to upload pages for this scan group'
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        DB::beginTransaction();

        try {
            $pageNumber = $version->pages()->max('page_number') ?? 0;

            foreach ($request->file('images') as $image) {
                $pageNumber++;

                // 📂 Ruta dinámica
                $path = $image->store(
                    "mangas/{$version->chapter->manga->id}/chapters/{$version->chapter_id}/versions/{$version->id}",
                    'public'
                );

                // 🌐 URL pública
                $url = asset("storage/" . $path);

                Page::create([
                    'chapter_version_id' => $version->id,
                    'page_number' => $pageNumber,
                    'image_url' => $url
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pages uploaded successfully'
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 🔄 Reemplazar páginas (URLs)
     */
    public function replacePages(Request $request, ChapterVersion $version): JsonResponse
    {
        if (!$request->user()->canUploadToScan($version->scan_group_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image'
        ]);

        DB::beginTransaction();

        try {
            // 🧹 borrar anteriores
            $version->pages()->delete();

            $pageNumber = 1;

            foreach ($request->file('images') as $image) {

                $path = $image->store(
                    "mangas/{$version->chapter->manga->id}/chapters/{$version->chapter_id}/versions/{$version->id}",
                    'public'
                );

                $url = asset("storage/" . $path);

                Page::create([
                    'chapter_version_id' => $version->id,
                    'page_number' => $pageNumber++,
                    'image_url' => $url
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pages replaced successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📚 Reader (ver capítulo)
     */
    public function reader(Request $request, $slug): JsonResponse
    {
        $version = ChapterVersion::where('slug', $slug)
            ->with([
                'chapter.manga',
                'language',
                'scanGroup',
                'pages' => fn($q) => $q->orderBy('page_number')
            ])
            ->first();

        if (!$version) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $chapter = $version->chapter;
        $manga = $chapter->manga;

        // 🔥 Obtener siguiente capítulo
        $nextChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        // 🔥 Obtener capítulo anterior
        $prevChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        // 👉 Obtener versión por defecto (primera disponible)
        $nextVersion = $nextChapter?->versions()
            ->where('language_id', $version->language_id)
            ->first()
            ?? $nextChapter?->versions()->first();

        $prevVersion = $prevChapter?->versions()
            ->where('language_id', $version->language_id)
            ->first()
            ?? $prevChapter?->versions()->first();

        // 👁️ Registrar vista
        View::firstOrCreate([
            'manga_id' => $manga->id,
            'ip_address' => $request->ip(),
            'user_id' => optional($request->user())->id,
        ]);

        return response()->json([
            'data' => [
                'manga' => $manga,
                'chapter' => $chapter,
                'version' => $version,
                'pages' => $version->pages,

                'navigation' => [
                    'next' => $nextVersion ? [
                        'chapter_number' => $nextChapter->chapter_number,
                        'slug' => $nextVersion->slug
                    ] : null,

                    'prev' => $prevVersion ? [
                        'chapter_number' => $prevChapter->chapter_number,
                        'slug' => $prevVersion->slug
                    ] : null,
                ]
            ]
        ], JsonResponse::HTTP_OK);
    }
}
