<?php

namespace App\Models;

use App\Models\ChapterVersion;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'website'])]
class ScanGroup extends Model
{
    public function chapterVersions(): HasMany
    {
        return $this->hasMany(ChapterVersion::class);
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}
