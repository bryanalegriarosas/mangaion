<?php 

namespace App\Models;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\MangaTitle;
use App\Models\Rating;
use App\Models\Tag;
use App\Models\View;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'normalized_title',
    'slug',
    'description',
    'cover_image',
    'status',
    'type',
    'author',
    'artist',
    'release_year',
    'created_by'
])]
class Manga extends Model
{
    protected $appends = ['cover_image_url'];
    // Más vistos esta semana
    public function scopePopularThisWeek(Builder $query): Builder
    {
        return $query->withCount(['views as weekly_views' => function ($q) {
            $q->where('created_at', '>=', now()->subWeek());
        }])
            ->orderByDesc('weekly_views');
    }

    // Tendencias (más vistos en general)
    public function scopeTrending(Builder $query): Builder
    {
        return $query
            ->withCount('views')
            ->orderByDesc('views_count');
    }

    protected function coverImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cover_image
                ? asset('storage/' . $this->cover_image)
                : null,
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function titles(): HasMany
    {
        return $this->hasMany(MangaTitle::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating(): float
    {
        return $this->ratings()->avg('rating');
    }
}
