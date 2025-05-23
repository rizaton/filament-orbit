<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tenda:
        Item::create([
            'name' => 'Tenda Kap. 2-3 Orang Compass 2 Alloy',
            'slug' => Str::slug(md5('Tenda Kap. 2-3 Orang Compass 2 Alloy')),
            'stock' => 10,
            'category_id' => 1,
            'is_available' => true,
            'image' => null,
            'sewa' => 30000,
        ]);
        Item::create([
            'name' => 'Tenda Kap. 4-5 Orang Tendaki Borneo 4',
            'slug' => Str::slug(md5('Tenda Kap. 4-5 Orang Tendaki Borneo 4')),
            'stock' => 10,
            'category_id' => 1,
            'is_available' => true,
            'image' => null,
            'sewa' => 40000,
        ]);
        Item::create([
            'name' => 'Tenda Kap. 6-7 Orang Tendaki Moluccas 6 Pro',
            'slug' => Str::slug(md5('Tenda Kap. 6-7 Orang Tendaki Moluccas 6 Pro')),
            'stock' => 10,
            'category_id' => 1,
            'is_available' => true,
            'image' => null,
            'sewa' => 65000,
        ]);

        // Perlengkapan Tidur:
        Item::create([
            'name' => 'Sleeping Bag',
            'slug' => Str::slug(md5('Sleeping Bag')),
            'stock' => 10,
            'category_id' => 2,
            'is_available' => true,
            'image' => null,
            'sewa' => 65000,
        ]);
        Item::create([
            'name' => 'Matras Aluminium Foil',
            'slug' => Str::slug(md5('Matras Aluminium Foil')),
            'stock' => 10,
            'category_id' => 2,
            'is_available' => true,
            'image' => null,
            'sewa' => 8000,
        ]);
        Item::create([
            'name' => 'Matras Spon',
            'slug' => Str::slug(md5('Matras Spon')),
            'stock' => 10,
            'category_id' => 2,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);

        // Perlengkapan Masak
        Item::create([
            'name' => 'Kompor Mawar/Kotak',
            'slug' => Str::slug(md5('Kompor Mawar/Kotak')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Kompor Grill',
            'slug' => Str::slug(md5('Kompor Grill')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 20000,
        ]);
        Item::create([
            'name' => 'Cookng Set DS 308',
            'slug' => Str::slug(md5('Cookng Set DS 308')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Grill Pan',
            'slug' => Str::slug(md5('Grill Pan')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 15000,
        ]);
        Item::create([
            'name' => 'Gas Refil + Kaleng',
            'slug' => Str::slug(md5('Gas Refil + Kaleng')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 14000,
        ]);
        Item::create([
            'name' => 'Gas Refil / Tukar Kaleng',
            'slug' => Str::slug(md5('Gas Refil / Tukar Kaleng')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 8000,
        ]);
        Item::create([
            'name' => 'Gelas Carabiner',
            'slug' => Str::slug(md5('Gelas Carabiner')),
            'stock' => 10,
            'category_id' => 3,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);

        // Perlengkapan Trekking & Survival
        Item::create([
            'name' => 'Sepatu Gunung',
            'slug' => Str::slug(md5('Sepatu Gunung')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 25000,
        ]);
        Item::create([
            'name' => 'Tas Gunung / Carrier 40L 60L 80L',
            'slug' => Str::slug(md5('Tas Gunung / Carrier 40L 60L 80L')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 30000,
        ]);
        Item::create([
            'name' => 'Drybag',
            'slug' => Str::slug(md5('Drybag')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Trekking Pole',
            'slug' => Str::slug(md5('Trekking Pole')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Topi Rimba',
            'slug' => Str::slug(md5('Topi Rimba')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Flysheet',
            'slug' => Str::slug(md5('Flysheet')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 15000,
        ]);
        Item::create([
            'name' => 'Tiang Flysheet',
            'slug' => Str::slug(md5('Tiang Flysheet')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Tali + Pasak Flysheet (6 Unit)',
            'slug' => Str::slug(md5('Tali + Pasak Flysheet (6 Unit)')),
            'stock' => 10,
            'category_id' => 4,
            'is_available' => true,
            'image' => null,
            'sewa' => 8000,
        ]);

        // Alat Penerangan
        Item::create([
            'name' => 'Headlamp Baterai',
            'slug' => Str::slug(md5('Headlamp Baterai')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Headlamp Charger',
            'slug' => Str::slug(md5('Headlamp Charger')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Lampu Tenda Baterai',
            'slug' => Str::slug(md5('Lampu Tenda Baterai')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Lampu Tenda Charger',
            'slug' => Str::slug(md5('Lampu Tenda Charger')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Lampu Tumblr',
            'slug' => Str::slug(md5('Lampu Tumblr')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Senter Baterai',
            'slug' => Str::slug(md5('Senter Baterai')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
        Item::create([
            'name' => 'Senter Charger',
            'slug' => Str::slug(md5('Senter Charger')),
            'stock' => 10,
            'category_id' => 5,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);

        // Perlengkapan Lainnya
        Item::create([
            'name' => 'Meja Lipat',
            'slug' => Str::slug(md5('Meja Lipat')),
            'stock' => 10,
            'category_id' => 6,
            'is_available' => true,
            'image' => null,
            'sewa' => 20000,
        ]);
        Item::create([
            'name' => 'Kursi Lipat',
            'slug' => Str::slug(md5('Kursi Lipat')),
            'stock' => 10,
            'category_id' => 6,
            'is_available' => true,
            'image' => null,
            'sewa' => 15000,
        ]);
        Item::create([
            'name' => 'Hammock',
            'slug' => Str::slug(md5('Hammock')),
            'stock' => 10,
            'category_id' => 6,
            'is_available' => true,
            'image' => null,
            'sewa' => 10000,
        ]);
        Item::create([
            'name' => 'Sarung Tangan',
            'slug' => Str::slug(md5('Sarung Tangan')),
            'stock' => 10,
            'category_id' => 6,
            'is_available' => true,
            'image' => null,
            'sewa' => 5000,
        ]);
    }
}
