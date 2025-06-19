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
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@orbitoutdoor.my.id',
            'email_verified_at' => now(),
            'is_admin' => true,
            'password' => bcrypt('admin'),
        ]);
        User::factory()->create([
            'name' => 'Tony Afriza',
            'email' => 'tonyafriza@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('tony'),
        ]);
        $this->call([
            CategorySeeder::class,
            ItemSeeder::class,
        ]);
    }
}
