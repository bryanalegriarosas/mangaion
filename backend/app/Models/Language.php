<?php

namespace App\Models;

use App\Models\ChapterVersion;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code'])]
class Language extends Model
{
    public $timestamps = false;

    public function chapterVersions(): HasMany
    {
        return $this->hasMany(ChapterVersion::class);
    }
}
