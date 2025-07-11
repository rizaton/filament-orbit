<?php

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Rental;
use App\Models\Category;
use App\Models\RentalDetail;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Menggunakan base TestCase dan trait RefreshDatabase agar database direset sebelum setiap test
uses(TestCase::class);
uses(RefreshDatabase::class);

// Menjalankan setup sebelum setiap pengujian
beforeEach(function () {
    // Mengatur panel Filament yang aktif ke 'admin'
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('bisa membuat sewa dengan detail sewa serta mengkalkulasi total', function () {
    // Membuat user dengan kota Jakarta (berpengaruh pada perhitungan down payment)
    $user = User::factory()->create([
        'city' => 'Jakarta',
    ]);

    // Membuat kategori item
    $category = Category::factory()->create();

    // Membuat item baru dalam kategori tersebut dengan stok awal 10 dan harga sewa 100.000
    $item = Item::factory()->create([
        'id_category' => $category->id_category,
        'stock' => 10,
        'rent_price' => 100000,
        'is_available' => true,
    ]);

    // Membuat rental baru dengan status pending dan durasi sewa 3 hari (hari ini + 2 hari)
    $rental = Rental::factory()->create([
        'id_user' => $user->id_user,
        'status' => 'pending',
        'rent_date' => now(),
        'return_date' => now()->addDays(2),
    ]);

    // Menambahkan detail rental: menyewa 2 unit dari item tersebut
    $rentalDetail = RentalDetail::factory()->create([
        'id_rental' => $rental->id_rental,
        'id_item' => $item->id_item,
        'quantity' => 2,
    ]);

    // Memuat ulang data rental untuk memastikan relasi dan atribut terbaru
    $rental->refresh()->load('rentalDetails.item');

    // Mengecek subtotal detail rental: 2 x 100.000 = 200.000
    expect($rentalDetail->sub_total)->toUseStrictEquality(200000.00);

    // Mengecek total biaya sewa: 200.000 x 3 hari = 600.000
    expect($rental->total_fees)->toUseStrictEquality(200000.00 * 3);

    // Mengecek uang muka (down payment): 25% dari 600.000 = 150.000 (karena user dari Jakarta)
    expect($rental->down_payment)->toUseStrictEquality(150000.00);

    // Mensimulasikan persetujuan rental
    $rental->update(['status' => 'approved']);

    // Memuat ulang data item untuk melihat perubahan stok
    $item->refresh();

    // Stok seharusnya berkurang 2 unit: 10 - 2 = 8
    expect($item->stock)->toBe(8);

    // Item tetap tersedia karena masih memiliki stok
    expect($item->is_available)->toBeTrue();
});

it('perbarui total biaya ketika tanggal kembali berganti', function () {
    $item = Item::factory()->create(['rent_price' => 100000]);
    $rental = Rental::factory()->create([
        'rent_date' => now()->toDateString(),
        'return_date' => now()->addDays(2)->toDateString(), // 3 hari
    ]);

    RentalDetail::factory()->create([
        'id_rental' => $rental->id_rental,
        'id_item' => $item->id_item,
        'quantity' => 2,
    ]);

    $rental->refresh();

    expect($rental->total_fees)->toEqual(100000 * 2 * 3); // 600.000
    expect($rental->down_payment)->toEqual(150000.00); // 25% dari 600.000

    // Mengubah tanggal kembali menjadi 5 hari lebih lama dari total
    $rental->return_date = $rental->rent_date->copy()->addDays(4); // 5 hari sewa
    $rental->save();
    $rental->recalculateTotals();

    $rental->refresh();

    expect($rental->total_fees)->toEqual(100000 * 2 * 5); // 1.000.000
    expect($rental->down_payment)->toEqual(250000.00);    // 25% dari 1.000.000
});
