<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContinueReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPages = $this->total_pages ?? 0;
        $lastPage   = $this->last_page   ?? 0;
 
        return [
            'manga' => $this->whenLoaded('manga', fn() => [
                'id'    => $this->manga->id,
                'title' => $this->manga->title,
                'slug'  => $this->manga->slug,
                'cover' => $this->manga->cover_image_url,
            ]),
 
            'chapter' => $this->whenLoaded('chapterVersion', fn() => [
                'id'             => $this->chapterVersion->id,
                'slug'           => $this->chapterVersion->slug,
                'title'          => $this->chapterVersion->title,
                'chapter_number' => $this->chapterVersion->chapter->chapter_number,
            ]),
 
            'page'       => $lastPage,
            'totalPages' => $this->whenLoaded('chapterVersion', 
                fn() => $this->chapterVersion->pages->count()),
            'progress'   => $totalPages > 0
                ? round(($lastPage / $totalPages) * 100)
                : 0,
        ];
    }
}
