<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReadingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadingProgressController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'manga_id' => 'required|exists:mangas,id',
            'chapter_id' => 'required|exists:chapters,id',
            'page' => 'required|integer|min:1',
        ]);

        $progress = ReadingProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'manga_id' => $request->manga_id,
            ],
            [
                'chapter_id' => $request->chapter_id,
                'page' => $request->page,
                'updated_at' => now(),
            ]
        );

        return response()->json($progress);
    }
}
