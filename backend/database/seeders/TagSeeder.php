<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::truncate();
        
        $tags = [
            'School',
            'Time Travel',
            'Reincarnation',
            'Magic',
            'Isekai',
            'Harem',
            'Villainess',
            'Monsters',
            'Demons',
            'Game',
            'Virtual Reality',
            'Military',
            'Survival'
        ];

        foreach ($tags as $tag) {

            $slug = Str::slug($tag);

            Tag::firstOrCreate([
                'slug' => $slug
            ], [
                'name' => $tag
            ]);
        }
    }
}
