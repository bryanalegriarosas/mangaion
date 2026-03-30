<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterVersionResource;
use App\Http\Resources\ContinueReadingResource;
use App\Http\Resources\MangaResource;
use App\Http\Resources\PopularMangaResource;
use App\Models\ChapterVersion;
use App\Models\Favorite;
use App\Models\Manga;
use App\Models\ReadingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const TRENDING_LIMIT    = 12;
    private const POPULAR_LIMIT     = 4;
    private const LATEST_LIMIT      = 20;
    private const CONTINUE_LIMIT    = 6;
    private const RECOMMENDED_LIMIT = 12;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'trending'       => $this->getTrending(),
            'popular'        => $this->getPopularThisWeek(),
            'latestChapters' => $this->getLatestChapters(),
            ...$user ? $this->getAuthData($user) : [],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // 🔥 TRENDING
    // ─────────────────────────────────────────────────────────────
    private function getTrending(): array
    {
        $mangas = Manga::query()
            ->withCount('views')
            ->with([
                'genres:id,name,slug',
                'ratings:id,manga_id,rating',
            ])
            ->orderByDesc('views_count')
            ->limit(self::TRENDING_LIMIT)
            ->get();

        return MangaResource::collection($mangas)->resolve();
    }

    // ─────────────────────────────────────────────────────────────
    // 📈 POPULAR ESTA SEMANA
    // ─────────────────────────────────────────────────────────────
    private function getPopularThisWeek(): array
    {
        $weekAgo     = now()->subDays(7);
        $twoWeeksAgo = now()->subDays(14);

        $mangas = Manga::query()
            ->withCount([
                'views as weekly_views' => fn($q) =>
                    $q->where('created_at', '>=', $weekAgo),

                'views as prev_views' => fn($q) =>
                    $q->whereBetween('created_at', [$twoWeeksAgo, $weekAgo]),
            ])
            ->with([
                'genres:id,name,slug',
                'ratings:id,manga_id,rating',
            ])
            ->orderByDesc('weekly_views')
            ->limit(self::POPULAR_LIMIT)
            ->get();

        return PopularMangaResource::collection($mangas)->resolve();
    }

    // ─────────────────────────────────────────────────────────────
    // 🆕 ÚLTIMOS CAPÍTULOS
    // ─────────────────────────────────────────────────────────────
    private function getLatestChapters(): array
    {
        $versions = ChapterVersion::query()
            ->with([
                'chapter:id,manga_id,chapter_number,volume_number',
                'chapter.manga:id,title,slug,cover_image',
                'language:id,name,code',
                'scanGroup:id,name',
            ])
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(self::LATEST_LIMIT)
            ->get();

        return ChapterVersionResource::collection($versions)->resolve();
    }

    // ─────────────────────────────────────────────────────────────
    // 🔐 DATOS PARA USUARIO AUTENTICADO
    // ─────────────────────────────────────────────────────────────
    private function getAuthData(mixed $user): array
    {
        return [
            'user'            => $this->formatUser($user),
            'continueReading' => $this->getContinueReading($user),
            'recommended'     => $this->getRecommended($user),
        ];
    }

    private function formatUser(mixed $user): array
    {
        $user->loadCount([
            'favorites',
            'readingHistory as chapters_read',
        ]);

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'username'     => $user->username,
            'avatar'       => $user->avatar
                ? asset('storage/' . $user->avatar)
                : null,
            'chaptersRead' => $user->chapters_read,
            'favorites'    => $user->favorites_count,
            'following'    => 0,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // ▶️ CONTINUAR LEYENDO
    // ─────────────────────────────────────────────────────────────
    private function getContinueReading(mixed $user): array
    {
        $history = ReadingHistory::query()
            ->where('user_id', $user->id)
            ->with([
                'manga:id,title,slug,cover_image',
                'chapterVersion:id,chapter_id,slug,title',
                'chapterVersion.chapter:id,chapter_number,volume_number',
                'chapterVersion.pages:id,chapter_version_id',
            ])
            ->latest('updated_at')
            ->limit(self::CONTINUE_LIMIT)
            ->get();

        return ContinueReadingResource::collection($history)->resolve();
    }

    // ─────────────────────────────────────────────────────────────
    // ⭐ RECOMENDADOS
    // ─────────────────────────────────────────────────────────────
    private function getRecommended(mixed $user): array
    {
        $favoriteGenreIds = Favorite::query()
            ->where('user_id', $user->id)
            ->join('genre_manga', 'favorites.manga_id', '=', 'genre_manga.manga_id')
            ->pluck('genre_manga.genre_id')
            ->unique();

        // Sin favoritos aún → devolver trending como fallback
        if ($favoriteGenreIds->isEmpty()) {
            return $this->getTrending();
        }

        $favoriteMangaIds = Favorite::query()
            ->where('user_id', $user->id)
            ->pluck('manga_id');

        $mangas = Manga::query()
            ->whereHas('genres', fn($q) =>
                $q->whereIn('genres.id', $favoriteGenreIds)
            )
            ->whereNotIn('id', $favoriteMangaIds)
            ->withCount('views')
            ->with([
                'genres:id,name,slug',
                'ratings:id,manga_id,rating',
            ])
            ->orderByDesc('views_count')
            ->limit(self::RECOMMENDED_LIMIT)
            ->get();

        return MangaResource::collection($mangas)->resolve();
    }
}
