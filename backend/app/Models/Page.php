<?php

namespace App\Models;

use App\Models\ChapterVersion;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['chapter_version_id', 'page_number', 'image_url'])]
class Page extends Model
{
    public function chapterVersion(): BelongsTo
    {
        return $this->belongsTo(ChapterVersion::class);
    }
}
