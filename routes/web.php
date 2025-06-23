<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/items', [ItemController::class, 'view'])->name('items');
Route::get('/items/{item:slug}', function (Item $item) {
    return view('item', [
        'item' => $item,
    ]);
})->name('item');
Route::get('/cart', function () {
    return view('cart', [
        'items' => Item::all(),
    ]);
})->name('cart');
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'create')->name('checkout.create');
    Route::post('/checkout', 'store')->name('checkout.store');
});
