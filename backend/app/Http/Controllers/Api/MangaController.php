<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class MangaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $mangas = Manga::latest()->paginate($request->input('per_page'));
        return response()->json($mangas);
    }

    public function show(Manga $manga): JsonResponse
    {
        return response()->json($manga);
    }
    
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|in:' . Manga::STATUS_ONGOING . ',' . Manga::STATUS_COMPLETED,
        ]);

        $slug = Str::slug($request->title);
        $count = Manga::where('slug', 'like', "{$slug}%")->count();
        $slug = $count > 0 ? "{$slug}-{$count}" : $slug;
   
        $manga = Manga::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status ?? Manga::STATUS_ONGOING,
            'author' => $request->author,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($manga, JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Manga $manga): JsonResponse
    {
        if ($manga->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $manga->update($request->only('title', 'description', 'status', 'author'));

        return response()->json($manga);
    }

    public function destroy(Request $request, Manga $manga): JsonResponse
    {
        if ($manga->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], JsonResponse::HTTP_FORBIDDEN);
        }

        $manga->delete();

        return response()->json(['message' => 'Deleted'], JsonResponse::HTTP_NO_CONTENT);
    }
}
