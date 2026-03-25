<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterVersion;
use App\Models\Manga;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
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
            ->with(['versions.language', 'versions.scanGroup'])
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
        $user = $request->user();

        // 🔐 Permisos
        if (!$user->hasRole('admin') && !$user->hasRole('uploader')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'chapter_number' => 'required|numeric',
            'volume_number' => 'nullable|integer'
        ]);

        // ❗ Evitar duplicado de capítulo
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
        $user = $request->user();

        if (!$user->hasRole('admin') && !$user->hasRole('uploader')) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'scan_group_id' => 'required|exists:scan_groups,id',
            'title' => 'nullable|string|max:255'
        ]);

        // ❗ Evitar duplicado (misma versión)
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

        // 🔥 Generar slug
        $baseSlug = "cap-{$chapter->chapter_number}-{$request->language_id}-{$request->scan_group_id}";
        $slug = Str::slug($baseSlug);

        $originalSlug = $slug;
        $count = 1;

        while (ChapterVersion::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
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
        $user = $request->user();

        if (!$user->hasRole('admin') && !$user->hasRole('uploader')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        DB::beginTransaction();

        try {
            $pageNumber = 1;

            foreach ($request->file('images') as $image) {

                // 📂 Ruta dinámica
                $path = $image->store(
                    "mangas/{$version->chapter->manga->id}/chapters/{$version->chapter_id}/versions/{$version->id}",
                    'public'
                );

                // 🌐 URL pública
                $url = asset("storage/" . $path);

                Page::create([
                    'chapter_version_id' => $version->id,
                    'page_number' => $pageNumber++,
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
     * 📚 Reader (ver capítulo)
     */
    public function reader($slug): JsonResponse
    {
        $version = ChapterVersion::where('slug', $slug)
            ->with([
                'chapter.manga',
                'language',
                'scanGroup',
                'pages'
            ])
            ->first();

        if (!$version) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'data' => $version
        ], JsonResponse::HTTP_OK);
    }
}
