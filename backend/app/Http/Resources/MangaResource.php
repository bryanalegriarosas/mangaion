<?php

namespace App\Http\Resources;

use App\Http\Resources\GenreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MangaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'slug'   => $this->slug,
            'cover'  => $this->cover_image_url,
            'status' => $this->status,
            'type'   => $this->type,
            'genre'  => $this->whenLoaded('genres',
                fn() => $this->genres->first()?->name
            ),
            'genres' => GenreResource::collection(
                $this->whenLoaded('genres')
            ),
            'rating' => $this->whenLoaded('ratings',
                fn() => $this->ratings->isNotEmpty()
                    ? round($this->ratings->avg('rating'), 1)
                    : null
            ),
        ];
    }
}
