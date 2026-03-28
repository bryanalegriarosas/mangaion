<?php

namespace App\Models;

use App\Models\Manga;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['name', 'slug', 'category'])]
class Tag extends Model
{

    // Categorías disponibles — usarlas como constantes
    // evita strings mágicos en el resto del código
    const CATEGORY_SETTING   = 'setting';
    const CATEGORY_FANTASY   = 'fantasy';
    const CATEGORY_CREATURE  = 'creature';
    const CATEGORY_CHARACTER = 'character';
    const CATEGORY_ROMANCE   = 'romance';
    const CATEGORY_THEMATIC  = 'thematic';
 
    const CATEGORIES = [
        self::CATEGORY_SETTING,
        self::CATEGORY_FANTASY,
        self::CATEGORY_CREATURE,
        self::CATEGORY_CHARACTER,
        self::CATEGORY_ROMANCE,
        self::CATEGORY_THEMATIC,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
