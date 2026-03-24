<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manga;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 🔥 TRENDING MANGAS
        $trending = Manga::query()
            ->select('id', 'title', 'slug', 'cover_image')
            ->latest()
            ->limit(12)
            ->get();

        // 🆕 ÚLTIMOS CAPÍTULOS
        $latestChapters = Chapter::query()
            ->with(['manga:id,title,slug,cover_image'])
            ->select('id', 'manga_id', 'title', 'chapter_number', 'created_at')
            ->latest()
            ->limit(12)
            ->get();

        // 📖 CONTINUE READING (solo si hay user)
        $continueReading = collect();
        $recommended = collect();

        if ($user) {

            // Continuar leyendo
            $continueReading = ReadingProgress::query()
                ->with([
                    'manga:id,title,slug,cover_image',
                    'chapter:id,title,chapter_number'
                ])
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();

            // ⭐ RECOMENDADOS (simple por ahora)
            // Basado en mangas que ya ha leído
            $mangaIds = $continueReading->pluck('manga_id');

            $recommended = Manga::query()
                ->whereNotIn('id', $mangaIds)
                ->inRandomOrder()
                ->limit(10)
                ->get(['id', 'title', 'slug', 'cover_image']);
        }

        return response()->json([
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
            ] : null,

            'trending' => $trending,

            'latestChapters' => $latestChapters->map(function ($chapter) {
                return [
                    'id' => $chapter->id,
                    'title' => $chapter->title,
                    'chapter_number' => $chapter->chapter_number,
                    'manga' => [
                        'id' => $chapter->manga->id,
                        'title' => $chapter->manga->title,
                        'slug' => $chapter->manga->slug,
                        'cover_image' => $chapter->manga->cover_image,
                    ]
                ];
            }),

            'continueReading' => $continueReading->map(function ($item) {
                return [
                    'manga' => [
                        'id' => $item->manga->id,
                        'title' => $item->manga->title,
                        'slug' => $item->manga->slug,
                        'cover_image' => $item->manga->cover_image,
                    ],
                    'chapter' => [
                        'id' => $item->chapter->id,
                        'title' => $item->chapter->title,
                        'chapter_number' => $item->chapter->chapter_number,
                    ],
                    'page' => $item->page,
                ];
            }),

            'recommended' => $recommended,
        ]);
    }
}
