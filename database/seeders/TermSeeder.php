<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Term::create([
            'slug' => md5('pengambilan-barang'),
            'name' => 'Pengambilan Barang',
            'content' => "Saat pengambilan perlengkapan/barang, setiap penyewa wajib meninggalkan kartu identitas berupa E-KTP/SIM/Kartu Pelajar / KTM (minimal angkatan diatas 2020), yang masih berlaku dan berdomisili di Jabodetabek. Untuk E-KTP di luar Jabodetabek bisa dikenakan jaminan uang senilai harga jual barang.",
        ]);
        Term::create([
            'slug' => md5('penjadwalan-perlengkapan'),
            'name' => 'Penjadwalan perlengkapan',
            'content' => "Penjadwalan perlengkapan persewaan hanya diperuntukan bagi penyewa/pelanggan yang telah membayar Down Payment (DP).",
        ]);
        Term::create([
            'slug' => md5('down-payment'),
            'name' => 'Down Payment',
            'content' => "Besarnya Down Payment (DP) adalah minimal 25% dari seluruh biaya sewa. Untuk pemesanan partai besar (seluruh biaya sewa diatas 2jt) minimal DP 50%",
        ]);
        Term::create([
            'slug' => md5('keterlambatan-pengembalian'),
            'name' => 'Keterlambatan Pengembalian',
            'content' => "Harga sewa adalah per 1 (satu) hari atau 24 jam dan minimal sewa 2 (dua) hari, jika terjadi keterlambatan pengembalian maka dianggap memperpanjang sewa (toleransi keterlambatan pengembalian 1-2 jam).",
        ]);
        Term::create([
            'slug' => md5('proses-sewa-1'),
            'name' => 'Proses Sewa 1',
            'content' => "Bawalah nota anda saat pengambilan maupun pengembalian perlengkapan/barang",
        ]);
        Term::create([
            'slug' => md5('proses-sewa-2'),
            'name' => 'Proses Sewa 2',
            'content' => "Perlengkapan/barang yang sudah dibawa keluar dari area persewaan Orbit Outdoor sudah dianggap sewa",
        ]);
        Term::create([
            'slug' => md5('Ketentuan-perubahan'),
            'name' => 'Ketentuan Perubahan',
            'content' => "Ketentuan tersebut di atas dapat berubah sesuai dengan situasi dan kondisi.",
        ]);
        Term::create([
            'slug' => md5('proses-sewa-3'),
            'name' => 'Proses Sewa 3',
            'content' => "Perlengkapan/barang yang sudah dibawa keluar dari area persewaan Orbit Outdoor sudah dianggap sewa",
        ]);
        Term::create([
            'slug' => md5('kesepakatan-penyewa'),
            'name' => 'Kesepakatan Penyewa',
            'content' => "Point-point tersebut di atas merupakan kesepakatan yang harus ditaati oleh penyewa.",
        ]);
        Term::create([
            'slug' => md5('selamat-berlibur'),
            'name' => 'Selamat Berlibur',
            'content' => "Selamat berlibur, tetap berhati-hati dan waspada. Adventure has no limit",
        ]);
    }
}
