<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\MangaController;
use App\Http\Controllers\Api\ScanGroupController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


//Rutas Publicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('home', [HomeController::class, 'index']);

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

Route::prefix('scan-groups')->group(function (): void {
    Route::get('', [ScanGroupController::class, 'index']);
    Route::get('{id}', [ScanGroupController::class, 'show']);
});

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
            Route::put('pages', [ChapterController::class, 'replacePages']);
        });
    });

    Route::prefix('scan-groups')->group(function (): void {
        Route::post('', [ScanGroupController::class, 'store']);
        
        Route::prefix('{id}')->group(function (): void {
            Route::delete('', [ScanGroupController::class, 'destroy']);
        });

        Route::prefix('{group}')->group(function (): void {

            Route::prefix('users')->group(function (): void {
                Route::post('', [ScanGroupController::class, 'addUser']);

                Route::prefix('{user}')->group(function (): void {
                    Route::put('', [ScanGroupController::class, 'updateUserRole']);
                    Route::delete('', [ScanGroupController::class, 'removeUser']);
                });
            });
        });
    });
});
