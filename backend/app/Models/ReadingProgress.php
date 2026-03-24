<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'manga_id', 'chapter_id', 'page', 'updated_at'])]
class ReadingProgress extends Model
{
    public $timestamps = false;

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
