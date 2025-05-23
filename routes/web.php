<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/items', function () {
    $itemsQuery = Item::filter(request(['search', 'category', 'sort']));

    if (request('paginate') === 'false') {
        $items = $itemsQuery->get();
    } else {
        $items = $itemsQuery->latest()->paginate(100)->withQueryString();
    }
    return view('items',  [
        'categories' => Category::all(),
        'items' => $items,
    ]);
})->name('items');

Route::get('/items/{item:slug}', function (Item $item) {
    return view('item', [
        'item' => $item,
    ]);
})->name('item');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    return view('contact');
})->name('contact.post');

Route::get('/cart', function () {
    return view('cart');
})->name('items');
