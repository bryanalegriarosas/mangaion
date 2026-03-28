<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        // Formato: ['name' => '...', 'category' => Tag::CATEGORY_*]
        $tags = [
            // ── Ambientación (setting) ───────────────────────────
            ['name' => 'School',           'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Office',           'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Medieval',         'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Post-Apocalyptic', 'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Dystopia',         'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Cyberpunk',        'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Steampunk',        'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Space',            'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Dungeon',          'category' => Tag::CATEGORY_SETTING],
            ['name' => 'Virtual Reality',  'category' => Tag::CATEGORY_SETTING],

            // ── Mecánicas de fantasía / isekai (fantasy) ─────────
            ['name' => 'Isekai',           'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Reincarnation',    'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Time Travel',      'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Magic',            'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'System',           'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'RPG',              'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Guild',            'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Overpowered MC',   'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Weak to Strong',   'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Cultivation',      'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Leveling Up',      'category' => Tag::CATEGORY_FANTASY],
            ['name' => 'Regression',       'category' => Tag::CATEGORY_FANTASY],

            // ── Criaturas / antagonistas (creature) ──────────────
            ['name' => 'Monsters',         'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Demons',           'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Vampires',         'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Zombies',          'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Dragons',          'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Gods',             'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Robots',           'category' => Tag::CATEGORY_CREATURE],
            ['name' => 'Aliens',           'category' => Tag::CATEGORY_CREATURE],

            // ── Personaje / rol del protagonista (character) ──────
            ['name' => 'Villainess',       'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Harem',            'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Reverse Harem',    'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Anti-Hero',        'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Hidden Identity',  'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Genius MC',        'category' => Tag::CATEGORY_CHARACTER],
            ['name' => 'Transmigration',   'category' => Tag::CATEGORY_CHARACTER],

            // ── Romance / relaciones (romance) ───────────────────
            ['name' => 'Love Triangle',     'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Childhood Friends', 'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Forbidden Love',    'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Age Gap',           'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Slow Burn',         'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Office Romance',    'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Arranged Marriage', 'category' => Tag::CATEGORY_ROMANCE],
            ['name' => 'Second Chance',     'category' => Tag::CATEGORY_ROMANCE],

            // ── Temáticos (thematic) ─────────────────────────────
            ['name' => 'Military',          'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Survival',          'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Game',              'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Sports',            'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Music',             'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Cooking',           'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Politics',          'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Investigation',     'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Revenge',           'category' => Tag::CATEGORY_THEMATIC],
            ['name' => 'Redemption',        'category' => Tag::CATEGORY_THEMATIC],
        ];

        Tag::upsert(
            collect($tags)->map(fn(array $tag) => [
                'name'     => $tag['name'],
                'slug'     => Str::slug($tag['name']),
                'category' => $tag['category'],
            ])->toArray(),
            uniqueBy: ['slug'],
            update:   ['name', 'category'],
        );
    }
}
