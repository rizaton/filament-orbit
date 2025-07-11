<?php

use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('bisa membuat kategori dengan alat yang berelasi', function () {
    // Membuat kategori
    $category = Category::factory()->create([
        'name' => 'Outdoor Gear',
        'slug' => 'outdoor-gear',
        'color' => '#ff9933',
    ]);

    // Membuat alat yang berelasi
    $items = Item::factory()->count(3)->create([
        'id_category' => $category->id_category,
    ]);

    // Merefresh daftar alat
    $category->load('items');

    // Pengecekan
    expect($category->items)->toHaveCount(3);

    foreach ($items as $item) {
        expect($item->category->id_category)->toEqual($category->id_category);
    }
});
