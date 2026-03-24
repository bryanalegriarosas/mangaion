<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index(Manga $manga): JsonResponse
    {
        return response()->json(
            $manga->chapters()
                ->orderBy('chapter_number', 'desc')
                ->get()
        );
    }
    
    public function show(Chapter $chapter): JsonResponse
    {
        $chapter->load([
            'pages' => fn($query) => $query->orderBy('page_number'),
        ]);

        return response()->json($chapter);
    }
    
    public function store(Request $request, Manga $manga): JsonResponse
    {
        // 🔐 Verificar dueño
        if ($manga->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // ✅ Validación
        $request->validate([
            'title' => 'nullable|string|max:255',
            'chapter_number' => 'required|numeric',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:5120', // 5MB cada imagen
        ]);

        // 📚 Crear capítulo
        $chapter = $manga->chapters()->create([
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
        ]);

        // 🖼️ Procesar imágenes
        foreach ($request->file('images') as $index => $image) {

            // 👉 TEMPORAL (luego R2)
            $path = $image->store('chapters', 'public');

            $chapter->pages()->create([
                'image_url' => $path,
                'page_number' => $index + 1,
            ]);
        }

        return response()->json([
            'message' => 'Chapter created',
            'chapter' => $chapter->load('pages')
        ], JsonResponse::HTTP_CREATED);
    }
}
