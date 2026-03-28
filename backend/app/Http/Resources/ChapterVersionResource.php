<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterVersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'title'        => $this->title,
            'published_at' => $this->published_at?->toISOString(),
 
            'chapter_number' => $this->whenLoaded('chapter',
                fn() => $this->chapter->chapter_number
            ),
            'volume_number' => $this->whenLoaded('chapter',
                fn() => $this->chapter->volume_number
            ),
 
            'language' => $this->whenLoaded('language', fn() => [
                'code' => $this->language->code,
                'name' => $this->language->name,
            ]),
 
            'scan_group' => $this->whenLoaded('scanGroup', fn() => [
                'id'   => $this->scanGroup->id,
                'name' => $this->scanGroup->name,
            ]),
 
            'manga' => $this->whenLoaded('chapter', function () {
                $manga = $this->chapter->manga;
                return [
                    'id'    => $manga->id,
                    'title' => $manga->title,
                    'slug'  => $manga->slug,
                    'cover' => $manga->cover_image_url,
                ];
            }),
        ];
    }
}
