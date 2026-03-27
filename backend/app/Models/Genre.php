<?php

namespace App\Models;

use App\Models\Manga;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug'])]
class Genre extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });
    }

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class);
    }
}
