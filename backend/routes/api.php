<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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

    Route::prefix('chapters')->group(function () {
        Route::prefix('{chapter}')->group(function () {
            Route::get('', [ChapterController::class, 'show']);
        });
    });
});

Route::get('/mangas', [MangaController::class, 'index']);
Route::get('/mangas/{manga}', [MangaController::class, 'show']);
