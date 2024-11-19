<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'supervisor',
                'guard_name' => 'web',
            ],
            [
                'name' => 'collector',
                'guard_name' => 'web',
            ],
        ]);

        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('collector');
        });

        User::factory(1)->create()->each(function ($user) {
            $user->assignRole('supervisor');
        });

        User::factory(1)->create()->each(function ($user) {
            $user->assignRole('admin');
        });

    }
}
