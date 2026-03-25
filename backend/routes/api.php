<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Support\Facades\Route;

//Rutas Publicas
Route::prefix('mangas')->group(function (): void {
    Route::get('', [MangaController::class, 'index']);

    Route::get('reader/{slug}', [ChapterController::class, 'reader']);
    Route::prefix('{slug}')->group(function (): void {
        Route::get('', [MangaController::class, 'show']);
        
        Route::prefix('chapters')->group(function (): void {
            Route::get('', [ChapterController::class, 'index']);
        });
    });
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Rutas Protegidas
Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('mangas')->group(function (): void {
        Route::post('', [MangaController::class, 'store']);

        Route::prefix('{manga}')->group(function (): void {
            Route::post('chapters', [ChapterController::class, 'store']);
        });

        Route::prefix('{slug}')->group(function (): void {
            Route::put('', [MangaController::class, 'update']);
            Route::delete('', [MangaController::class, 'destroy']);
        });
    });

    Route::prefix('chapters')->group(function (): void {
        Route::prefix('{chapter}')->group(function (): void {
            Route::post('versions', [ChapterController::class, 'storeVersion']);
        });
    });

    Route::prefix('versions')->group(function (): void {
        Route::prefix('{version}')->group(function (): void {
            Route::post('pages', [ChapterController::class, 'storePages']);
        });
    });
});
