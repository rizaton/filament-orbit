<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MessageController;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
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

Route::controller(MessageController::class)->group(function () {
    Route::get('/contact', 'create')->name('contact.create');
    Route::post('/contact', 'store')->name('contact.store');
});

Route::get('/cart', function () {
    return view('cart');
})->name('items');

Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'create')->name('checkout.create');
    Route::post('/checkout', 'store')->name('checkout.store');
});
