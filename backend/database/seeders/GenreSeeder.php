<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            // ── Géneros narrativos ──────────────────────────────
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Romance',
            'Horror',
            'Mystery',
            'Sci-Fi',
            'Thriller',
            'Supernatural',
            'Psychological',
            'Slice of Life',

            // ── Demografías ─────────────────────────────────────
            'Shounen',       // dirigido a jóvenes varones
            'Shoujo',        // dirigido a jóvenes mujeres
            'Seinen',        // adultos masculinos
            'Josei',         // adultas femeninas
            'Kodomomuke',    // infantil

            // ── Subgéneros / temáticos ──────────────────────────
            'Mecha',
            'Martial Arts',
            'Sports',
            'Music',
            'Cooking',
            'Historical',
            'Military',
            'Isekai',
            'Ecchi',
            'Harem',
            'Reverse Harem',
            'Yaoi',
            'Yuri',
        ];

        Genre::upsert(
            collect($genres)->map(fn(string $name) => [
                'name' => $name,
                'slug' => Str::slug($name),
            ])->toArray(),
            uniqueBy: ['slug'],
            update:   ['name'],
        );
    }
}
