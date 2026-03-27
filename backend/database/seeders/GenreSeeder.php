<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        Genre::truncate();
        
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Romance',
            'Slice of Life',
            'Horror',
            'Mystery',
            'Sci-Fi',
            'Supernatural',
            'Sports',
            'Psychological'
        ];

        foreach ($genres as $genre) {

            $slug = Str::slug($genre);

            Genre::firstOrCreate([
                'slug' => $slug
            ], [
                'name' => $genre
            ]);
        }
    }
}
