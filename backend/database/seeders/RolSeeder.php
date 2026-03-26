<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();

        $roles = [
            Role::SUPER_ADMIN,
            Role::ADMIN,
            Role::OWNER,
            Role::UPLOADER,
            Role::EDITOR,
            Role::USER,
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
