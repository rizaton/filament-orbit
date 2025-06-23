<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function view(Request $request): View
    {
        $itemsQuery = Item::filter($request->only(['search', 'category', 'sort']))->where('is_available', true);
        if (request('paginate') === 'false') {
            $items = $itemsQuery->get();
        } else {
            $items = $itemsQuery->latest()->paginate(100)->withQueryString();
        }
        return view('items',  [
            'categories' => Category::all(),
            'items' => $items,
        ]);
    }
}
