<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::truncate();
        
        $languages = [
            [
                'code' => 'es',
                'name' => 'Español',
            ],
            [
                'code' => 'en',
                'name' => 'English',
            ],
        ];
        
        foreach ($languages as $language) {
            Language::firstOrCreate(['code' => $language['code']], ['name' => $language['name']]);
        }
    }
}
