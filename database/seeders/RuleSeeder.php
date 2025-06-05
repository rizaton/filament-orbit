<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rule::create([
            'slug' => md5('pengambilan-barang-1'),
            'name' => 'Pengambilan Barang 1',
            'content' => "Penyewa wajib mengambil barang sesuai dengan tanggal pemesanan dan mengembalikan barang sesuai dengan tanggal pengembalian, bila ada penambahan masa sewa harap konfirmasi ke WA admin.",
        ]);
        Rule::create([
            'slug' => md5('pengambilan-barang-2'),
            'name' => 'Pengambilan Barang 2',
            'content' => "Penyewa wajib mengecek semua perlengkapan yang disewa sebelum meninggalkan lokasi persewaan ORBIT OUTDOOR. Kami tidak menerima komplain dalam bentuk apapun apabila penyewa telah meninggalkan lokasi persewaan.",
        ]);
        Rule::create([
            'slug' => md5('keadaan-barang-1'),
            'name' => 'Keadaan Barang 1',
            'content' => "Peralatan/perlengkapan persewaan dalam kondisi baik dan utuh, penyewa wajib merawat dan menjaganya dengan baik. Jauhkan barang dari api, kecuali kompor dan nesting. Dilarang merokok dan memasak di dalam tenda. Dilarang mendirikan tenda terlalu dekat api unggun. Dilarang mendirikan tenda di lahan yang berbatu dan terdapat benda tajam lainya. Membawa barang dengan baik dan benar.",
        ]);
        Rule::create([
            'slug' => md5('keterlambatan-pengembalian'),
            'name' => 'Keterlambatan Pengembalian',
            'content' => "Harga sewa adalah per 1 (satu) hari atau 24 jam dan minimal sewa 2 (dua) hari, jika terjadi keterlambatan pengembalian maka dianggap memperpanjang sewa (toleransi keterlambatan pengembalian 1-2 jam).",
        ]);
        Rule::create([
            'slug' => md5('keadaan-barang-2'),
            'name' => 'Keadaan Barang 2',
            'content' => "Apabila terjadi kerusakan atau hilang terhadap peralatan yang di sewa, penyewa bertanggung jawab sepenuhnya untuk memperbaiki atau mengganti peralatan yang sama/serupa. (untuk keterangan denda dan kerusakan bisa dilihat di KITAB UNDANG-UNDANG PERSEWAAN KERUSAKAN/DENDA).",
        ]);
        Rule::create([
            'slug' => md5('peringatan-1'),
            'name' => 'Peringatan 1',
            'content' => "Apabila terjadi kerusakan atau hilang terhadap peralatan yang di sewa, penyewa bertanggung jawab sepenuhnya untuk memperbaiki atau mengganti peralatan yang sama/serupa. (untuk keterangan denda dan kerusakan bisa dilihat di KITAB UNDANG-UNDANG PERSEWAAN KERUSAKAN/DENDA).",
        ]);
        Rule::create([
            'slug' => md5('peringatan-2'),
            'name' => 'Peringatan 2',
            'content' => "Persiapkanlah kegiatan anda dengan baik (manajemen waktu, manajemen perjalanan dan manajemen perlengkapan) karena kegiatan outdoor adalah kegiatan yang berisiko tinggi dan sulit diprediksi.",
        ]);
        Rule::create([
            'slug' => md5('ketentuan-1'),
            'name' => 'Ketentuan 1',
            'content' => "Ketentuan tersebut di atas dapat berubah sesuai dengan situasi dan kondisi.",
        ]);
        Rule::create([
            'slug' => md5('ketentuan-2'),
            'name' => 'Ketentuan 2',
            'content' => "Point-point tersebut di atas merupakan kesepakatan yang harus ditaati oleh penyewa.",
        ]);
        Rule::create([
            'slug' => md5('selamat'),
            'name' => 'Selamat',
            'content' => "Selamat berlibur, tetap berhati-hati dan waspada. Adventure has no limit.",
        ]);
    }
}
