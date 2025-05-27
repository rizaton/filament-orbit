<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rule;
use App\Models\Term;
use App\Models\Rental;
use App\Models\RentalDetail;


use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     *
     * @return View
     */
    public function create(): View
    {

        return view('checkout', [
            'terms' => Term::all(['slug', 'name', 'description']),
            'rules' => Rule::all(['slug', 'name', 'description']),
        ]);
    }

    /**
     * Menyimpan data checkout yang dikirimkan dari form.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:20'],
            'cart' => ['required', 'array'],
            'terms' => ['accepted'],
            'rules' => ['accepted'],
            'rentDate' => ['required', 'date'],
            'returnDate' => ['required', 'date', 'after_or_equal:rentDate'],
        ]);
        // dd($data);
        try {
            $fullName = $data['firstName'] . ' ' . $data['lastName'];
            $address = $data['address'];
            $phone = $data['phone'];
            $rentDate = $data['rentDate'];
            $returnDate = $data['returnDate'];
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors([
                'status' => 'invalid-checkout',
                'message' => 'Informasi checkout tidak lengkap atau tidak valid.'
            ]);
        }
        try {
            $dirtyCarts = collect($data['cart'])->map(function ($cartItem) {
                return [
                    'item' => Item::all()->where('slug', '=', $cartItem['slug'])->first()->toArray(),
                    'quantity' => $cartItem['qty'],
                    'down_payment' => 0,
                ];
            })->toArray();
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors([
                'status' => 'invalid-cart',
                'message' => 'Data keranjang tidak valid atau tidak lengkap.'
            ]);
        }
        // dd($dirtyCarts);
        try {
            $rent = Rental::create([
                'name' => $fullName,
                'address' => $address,
                'phone' => $phone,
                'down_payment' => 0,
                'rent_date' => $rentDate,
                'return_date' => $returnDate,
            ]);
            $rentalId = $rent->id;
            $rentalDetails = [];

            foreach ($dirtyCarts as $key => $dirtyCartItem) {
                $itemDetails = $dirtyCartItem['item'];
                $quantity = $dirtyCartItem['quantity'];
                $subTotal = $itemDetails['rent_price'] * $quantity;

                $rentalDetails[$key]['rental_id'] = $rentalId;
                $rentalDetails[$key]['item_id'] = $itemDetails['id'];
                $rentalDetails[$key]['quantity'] = $quantity;
                $rentalDetails[$key]['is_returned'] = false;
                $rentalDetails[$key]['sub_total'] = $subTotal;
            }

            dd(RentalDetail::insert($rentalDetails));
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors([
                'status' => 'invalid-checkout',
                'message' => 'Gagal menyimpan data checkout. Silakan coba lagi.'
            ]);
        }
        dd($rentalDetails);
        try {
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors([
                'status' => 'invalid-cart',
                'message' => 'Invalid cart data provided.'
            ]);
        }

        return Redirect::route('checkout.create')->with('status', 'checkout-sent');
    }
}
