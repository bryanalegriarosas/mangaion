<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Language;
use App\Models\Page;
use App\Models\ScanGroup;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['chapter_id', 'language_id', 'scan_group_id', 'title', 'slug', 'published_at'])]
class ChapterVersion extends Model
{
    public function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function scanGroup(): BelongsTo
    {
        return $this->belongsTo(ScanGroup::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
