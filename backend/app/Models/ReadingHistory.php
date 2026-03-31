<?php

namespace App\Models;

use App\Models\ChapterVersion;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'manga_id', 'chapter_version_id', 'last_page'])]
class ReadingHistory extends Model
{
    protected $table = 'reading_history';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function chapterVersion(): BelongsTo
    {
        return $this->belongsTo(ChapterVersion::class);
    }
}
