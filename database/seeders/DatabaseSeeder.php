<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'is_admin' => false,
            'email_verified_at' => now(),
            'password' => bcrypt('johndoe'),
        ]);
        $this->call([
            CategorySeeder::class,
            ItemSeeder::class,
            UserSeeder::class,
        ]);
    }
}
