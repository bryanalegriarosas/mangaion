<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    public function index(): JsonResponse
    {
        $genres = Genre::orderBy('name')->get(['id', 'name', 'slug']);
        return response()->json($genres, JsonResponse::HTTP_OK);
    }
}
