<?php

namespace App\Models;

use App\Models\ChapterVersion;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['manga_id', 'chapter_number', 'volume_number'])]
class Chapter extends Model
{
    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ChapterVersion::class);
    }
}
