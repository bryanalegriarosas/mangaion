<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'manga_id',
    'title',
    'normalized_title',
    'type'
])]
class MangaTitle extends Model
{
    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }
}
