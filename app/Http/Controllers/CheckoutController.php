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
            'items' => Item::all(['slug', 'name', 'rent_price', 'image']),
            'terms' => Term::all(['slug', 'name', 'content']),
            'rules' => Rule::all(['slug', 'name', 'content']),
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
        try {
            return Redirect::to('/customer/login')->with([
                'status' => 'checkout-redirect',
                'message' => 'Silakan login atau buat akun untuk melanjutkan sewa.'
            ]);
        } catch (\Throwable $th) {
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
                    ];
                })->toArray();

                $rent_total = collect($dirtyCarts)->sum(function ($dirtyCartItem) {
                    return $dirtyCartItem['item']['rent_price'] * $dirtyCartItem['quantity'];
                });

                if ($rent_total > 2000000) {
                    $down_payment = $rent_total * 0.5;
                } else {
                    $down_payment = $rent_total * 0.25;
                }
            } catch (\Throwable $th) {
                return Redirect::back()->withErrors([
                    'status' => 'invalid-cart',
                    'message' => 'Data keranjang tidak valid atau tidak lengkap.'
                ]);
            }

            try {
                $rent = Rental::create([
                    'name' => $fullName,
                    'address' => $address,
                    'phone' => $phone,
                    'rent_date' => $rentDate,
                    'return_date' => $returnDate,
                    'down_payment' => $down_payment,
                    'total_fees' => $rent_total,
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

                $status = RentalDetail::insert($rentalDetails);
            } catch (\Throwable $th) {
                return Redirect::back()->withErrors([
                    'status' => 'invalid-checkout',
                    'message' => 'Gagal menyimpan data checkout. Silakan coba lagi.'
                ]);
            }
            if ($status) {
                $pesan = "Halo, Orbit Outdoor\n\n";
                $pesan .= "Saya ingin melakukan pemesanan rental berikut:\n";
                $pesan .= "Nama: $fullName\n";
                $pesan .= "Alamat: $address\n";
                $pesan .= "No. Telepon: $phone\n";
                $pesan .= "Tanggal Sewa: $rentDate\n";
                $pesan .= "Tanggal Kembali: $returnDate\n\n";
                $pesan .= "Ingin menyewa:\n";
                foreach ($dirtyCarts as $cartItem) {
                    $item = $cartItem['item'];
                    $quantity = $cartItem['quantity'];
                    $pesan .= "- {$item['name']} (x{$quantity})\n";
                }
                $pesan .= "Apakah tersedia untuk disewa?\nTerima kasih!";
                $pesan = urlencode($pesan);
                $phone = "6285281981717";

                $whatsappUrl = "https://api.whatsapp.com/send?phone={$phone}&text={$pesan}";

                return Redirect::to($whatsappUrl);
            } else {
                return Redirect::back()->withErrors([
                    'status' => 'invalid-checkout',
                    'message' => 'Gagal proses redirect whatsapp. Silakan coba lagi.'
                ]);
            }
            return Redirect::route('checkout.create')->with('status', 'checkout-sent')->withErrors(
                [$th]
            );
        }
    }
}
