<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Tampil view daftar item.
     *
     * @return View
     * @param Request $request
     */
    public function view(Request $request): View
    {
        /**
         * Menggunakan query builder untuk memfilter item berdasarkan parameter yang diberikan.
         * Parameter yang didukung: 'search', 'category', dan 'sort'.
         * 'search' untuk mencari nama item,
         * 'category' untuk memfilter berdasarkan kategori,
         * 'sort' untuk mengurutkan item.
         * Jika tidak ada parameter yang diberikan, akan mengambil semua item.
         * @see \App\Models\Item
         */
        $itemsQuery = Item::filter($request->only(['search', 'category', 'sort']));

        /**
         * Jika parameter 'paginate' diatur ke 'false', ambil semua item.
         * Jika tidak, ambil item terbaru dengan paginasi 100 item per halaman.
         * Dengan menggunakan `withQueryString()` untuk mempertahankan query string saat paginasi.
         */
        if (request('paginate') === 'false') {
            $items = $itemsQuery->get();
        } else {
            $items = $itemsQuery->latest()->paginate(100)->withQueryString();
        }

        /**
         * Menggunakan eager loading untuk mengurangi jumlah query yang dieksekusi.
         * Dengan eager loading, dapat menghindari N+1 query problem saat mengambil kategori dari setiap item.
         * Dengan menggunakan `with(['category'])`,
         * memastikan bahwa kategori untuk setiap item sudah dimuat sebelumnya.
         * @see \App\Models\Category
         */
        return view('items',  [
            'categories' => Category::all(),
            'items' => $items,
        ]);
    }
}
