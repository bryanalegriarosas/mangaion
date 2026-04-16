<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::orderBy('category')->orderBy('name')
            ->get(['id', 'name', 'slug', 'category']);

        $grouped = $tags->groupBy('category')->map(fn ($group) => 
            $group->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->values()
        );

        return response()->json([
            'data' => $tags,
            'grouped' => $grouped,
        ], JsonResponse::HTTP_OK);
    }
}
