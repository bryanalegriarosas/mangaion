<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MangaController;
use App\Http\Controllers\Api\ScanGroupController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

// ─── Rutas Públicas ───────────────────────────────────────────────
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);
Route::get('home',      [HomeController::class, 'index']);
Route::get('genres', [GenreController::class, 'index']);
Route::get('tags',   [TagController::class, 'index']);

Route::prefix('mangas')->group(function (): void {
    Route::get('', [MangaController::class, 'index']);

    Route::get('reader/{slug}', [ChapterController::class, 'reader']);

    Route::prefix('{manga}')->whereNumber('manga')->group(function (): void {
        Route::get('', [MangaController::class, 'show']);

        Route::prefix('chapters')->group(function (): void {
            Route::get('', [ChapterController::class, 'index']);
        });
    });
});

Route::prefix('scan-groups')->group(function (): void {
    Route::get('', [ScanGroupController::class, 'index']);

    // whereNumber evita que /scan-groups/mine caiga aquí
    Route::get('{id}', [ScanGroupController::class, 'show'])
        ->whereNumber('id');
});

// ─── Rutas Protegidas ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('user',    [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // ── Mangas ────────────────────────────────────────────────────
    Route::prefix('mangas')->group(function (): void {
        Route::get('mine', [MangaController::class, 'myMangas']);
        Route::post('',    [MangaController::class, 'store']);

        Route::prefix('{manga}')->whereNumber('manga')->group(function (): void {
            Route::post('chapters', [ChapterController::class, 'store']);
            Route::put('',         [MangaController::class, 'update']);
            Route::delete('',      [MangaController::class, 'destroy']);
        });
    });

    // ── Capítulos ─────────────────────────────────────────────────
    Route::prefix('chapters')->group(function (): void {
        Route::prefix('{chapter}')->whereNumber('chapter')->group(function (): void {
            Route::post('versions', [ChapterController::class, 'storeVersion']);
        });
    });

    // ── Versiones de capítulos ────────────────────────────────────
    Route::prefix('chapter-versions')->group(function (): void {
        Route::get('mine', [ChapterController::class, 'myVersions']);

        Route::prefix('{version}')->whereNumber('version')->group(function (): void {
            Route::post('pages', [ChapterController::class, 'storePages']);
            Route::put('pages',  [ChapterController::class, 'replacePages']);
        });
    });

    // ── Scan Groups ───────────────────────────────────────────────
    Route::prefix('scan-groups')->group(function (): void {
        Route::get('mine', [ScanGroupController::class, 'myGroups']);
        Route::post('',    [ScanGroupController::class, 'store']);

        Route::prefix('{id}')->whereNumber('id')->group(function (): void {
            Route::delete('', [ScanGroupController::class, 'destroy']);
        });

        Route::prefix('{group}')->whereNumber('group')->group(function (): void {
            Route::prefix('users')->group(function (): void {
                Route::post('', [ScanGroupController::class, 'addUser']);

                Route::prefix('{user}')->whereNumber('user')->group(function (): void {
                    Route::put('',    [ScanGroupController::class, 'updateUserRole']);
                    Route::delete('', [ScanGroupController::class, 'removeUser']);
                });
            });
        });
    });
});
