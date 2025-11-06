<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
            ]);
        }

        $roleClass = 'Spatie\\Permission\\Models\\Role';
        $role = $roleClass::findOrCreate('super-admin');
        $user->assignRole($role);
    }
}
