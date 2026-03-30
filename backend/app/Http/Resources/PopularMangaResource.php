<?php

namespace App\Http\Resources;

use App\Http\Resources\MangaResource;
use Illuminate\Http\Request;

class PopularMangaResource extends MangaResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = parent::toArray($request);

        return array_merge($parent, [
            'readers' => (int) $this->weekly_views, // campo calculado por withCount
            'growth'  => $this->calculateGrowth(),
        ]);
    }
    
    private function calculateGrowth(): ?int
    {
        $weekly = (int) $this->weekly_views;
        $prev   = (int) $this->prev_views;

        return $prev > 0
            ? (int) round((($weekly - $prev) / $prev) * 100)
            : null;
    }
}
