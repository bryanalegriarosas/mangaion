<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MangaController;
use App\Http\Controllers\Api\ReadingProgressController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Auth
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::prefix('mangas')->group(function () {
        Route::apiResource('', MangaController::class)->only(['store', 'update', 'destroy']);

        Route::prefix('{manga}')->group(function () {
            Route::get('chapters', [ChapterController::class, 'index']);
            Route::post('chapters', [ChapterController::class, 'store']);
        });
    });

    Route::post('reading-progress', [ReadingProgressController::class, 'store']);
});

//Public
Route::get('home', [HomeController::class, 'index']);

Route::prefix('mangas')->group(function (): void {
    Route::get('', [MangaController::class, 'index']);
    Route::prefix('{manga}')->group(function (): void {
        Route::get('', [MangaController::class, 'show']);
    });
});

Route::prefix('chapters')->group(function (): void {
    Route::prefix('{chapter}')->group(function (): void {
        Route::get('', [ChapterController::class, 'show']);
    });
});
