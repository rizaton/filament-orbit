<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Tampil form kontak.
     *
     * @return View
     */
    public function create(): View
    {
        return view('contact');
    }

    /**
     * Menyimpan pesan baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        /**
         * Validasi input dari form kontak.
         * Menggunakan aturan validasi untuk memastikan bahwa:
         * - 'name' harus diisi, berupa string, dengan panjang maksimal 255 karakter dan minimal 3 karakter.
         * - 'email' harus diisi, berupa email yang valid, dalam huruf kecil, dengan panjang maksimal 255 karakter.
         * - 'subject' harus diisi, berupa string, dengan panjang maksimal 255 karakter dan minimal 3 karakter.
         * - 'message' harus diisi, berupa string, dengan panjang maksimal 500 karakter dan minimal 10 karakter.
         * Jika validasi gagal, akan mengembalikan pesan kesalahan yang sesuai.
         * Jika validasi berhasil, data akan disimpan ke dalam model Message.
         * Setelah penyimpanan, pengguna akan diarahkan kembali ke halaman form kontak
         */
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'lowercase', 'max:255'],
            'subject' => ['required', 'string', 'max:255', 'min:3'],
            'message' => ['required', 'string', 'max:500', 'min:10'],
        ]);

        /**
         * Menyimpan data pesan baru ke dalam model Message.
         * Data yang disimpan meliputi:
         * - 'name': nama pengirim pesan.
         * - 'email': alamat email pengirim pesan.
         * - 'subject': subjek pesan.
         * - 'message': isi pesan yang dikirimkan.
         * Dengan menggunakan metode `create()`, data akan disimpan ke dalam tabel 'messages'.
         * Pastikan model Message sudah memiliki atribut yang dapat diisi secara massal (fillable) untuk menghindari MassAssignmentException.
         * @see \App\Models\Message
         */
        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        /**
         * Setelah pesan berhasil disimpan, pengguna akan diarahkan kembali ke halaman form kontak
         * dengan membawa status 'message-sent'.
         * Status ini dapat digunakan untuk menampilkan pesan sukses di halaman form kontak.
         * Dengan menggunakan `Redirect::route()`, kita mengarahkan pengguna ke rute 'contact.create'.
         * Pastikan rute tersebut sudah didefinisikan dalam file routes/web.php.
         * @see routes/web.php
         * @return RedirectResponse
         */
        return Redirect::route('contact.create')->with('status', 'message-sent');
    }
}
