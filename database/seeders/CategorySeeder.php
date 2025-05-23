<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'color' => '#FF5733',
        ]);
        Category::create([
            'name' => 'Perlengkapan Tidur',
            'slug' => 'perlengkapan-tidur',
            'color' => '#FF8D33',
        ]);
        Category::create([
            'name' => 'Perlengkapan Masak',
            'slug' => 'perlengkapan-masak',
            'color' => '#33FF57',
        ]);
        Category::create([
            'name' => 'Perlengkapan Trekking & Survival',
            'slug' => 'perlengkapan-trekking-survival',
            'color' => '#3357FF',
        ]);
        Category::create([
            'name' => 'Alat Penerangan',
            'slug' => 'alat-penerangan',
            'color' => '#FF33A1',
        ]);
        Category::create([
            'name' => 'Perlengkapan lainnya',
            'slug' => 'perlengkapan-lainnya',
            'color' => '#FF33FF',
        ]);
    }
}
