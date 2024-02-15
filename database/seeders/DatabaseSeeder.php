<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        Role::firstOrCreate(['name' => 'guest']);
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'admin']);

        // Seed initial admin user
        $adminRole = Role::where('name', 'admin')->first();
        User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Check based on a unique identifier
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id
            ]
        );
    }
}