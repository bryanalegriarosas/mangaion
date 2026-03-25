<?php

namespace App\Models;

use App\Models\Manga;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'slug'])]
class Genre extends Model
{
    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class);
    }
}
