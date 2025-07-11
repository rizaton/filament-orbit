<?php

namespace Database\Seeders;

use App\Models\Item;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    private function parseImage(string $path): string
    {
        $manager = new ImageManager(new Driver());
        $fullPath = database_path('seeders/assets/' . $path);
        $image = $manager->read($fullPath)
            ->scale(width: 500);
        $encoded = $image->encodeByMediaType('image/jpg', quality: 50);
        $base64 = $encoded->toDataUri();
        return $base64;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category Tenda
        Item::create([
            'name' => 'Tenda Kap. 2-3 Orang Compass 2 Alloy',
            'slug' => Str::slug(md5('Tenda Kap. 2-3 Orang Compass 2 Alloy')),
            'stock' => 10,
            'id_category' => 1,
            'description' => 'Tenda ringan berkapasitas 2-3 orang dengan bahan alloy dari Compass, ideal untuk pendakian ringan dan camping pasangan.',
            'is_available' => true,
            'image' => $this->parseImage('tenda/compass.jpg'),
            'rent_price' => 30000,
        ]);

        Item::create([
            'name' => 'Tenda Kap. 4-5 Orang Tendaki Borneo 4',
            'slug' => Str::slug(md5('Tenda Kap. 4-5 Orang Tendaki Borneo 4')),
            'stock' => 10,
            'id_category' => 1,
            'description' => 'Tenda kapasitas 4-5 orang cocok untuk keluarga kecil atau grup. Model Borneo 4 dari Tendaki menawarkan kenyamanan dan daya tahan.',
            'is_available' => true,
            'image' => $this->parseImage('tenda/borneo.jpeg'),
            'rent_price' => 40000,
        ]);

        Item::create([
            'name' => 'Tenda Kap. 6-7 Orang Tendaki Moluccas 6 Pro',
            'slug' => Str::slug(md5('Tenda Kap. 6-7 Orang Tendaki Moluccas 6 Pro')),
            'stock' => 10,
            'id_category' => 1,
            'description' => 'Tenda besar untuk 6-7 orang dengan desain lapang dan ventilasi maksimal. Moluccas 6 Pro dari Tendaki cocok untuk perjalanan berkelompok.',
            'is_available' => true,
            'image' => $this->parseImage('tenda/mollucas.jpeg'),
            'rent_price' => 65000,
        ]);

        // Perlengkapan Tidur
        Item::create([
            'name' => 'Sleeping Bag',
            'slug' => Str::slug(md5('Sleeping Bag')),
            'stock' => 10,
            'id_category' => 2,
            'description' => 'Sleeping bag hangat dan nyaman untuk menjaga suhu tubuh saat tidur di alam terbuka. Ideal untuk cuaca dingin saat camping atau hiking.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-tidur/sleepingbag.jpg'),
            'rent_price' => 65000,
        ]);

        Item::create([
            'name' => 'Matras Aluminium Foil',
            'slug' => Str::slug(md5('Matras Aluminium Foil')),
            'stock' => 10,
            'id_category' => 2,
            'description' => 'Matras ringan dengan lapisan aluminium foil untuk memantulkan panas tubuh, memberikan isolasi yang baik dari tanah dingin.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-tidur/aluminiumfoil.jpeg'),
            'rent_price' => 8000,
        ]);

        Item::create([
            'name' => 'Matras Spon',
            'slug' => Str::slug(md5('Matras Spon')),
            'stock' => 10,
            'id_category' => 2,
            'description' => 'Matras spon empuk untuk memberikan kenyamanan tambahan saat tidur. Mudah digulung dan cocok untuk kegiatan outdoor.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-tidur/matras.jpeg'),
            'rent_price' => 5000,
        ]);

        // Perlengkapan Masak
        Item::create([
            'name' => 'Kompor Mawar/Kotak',
            'slug' => Str::slug(md5('Kompor Mawar/Kotak')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Kompor portable tipe mawar atau kotak, praktis digunakan di alam terbuka. Cocok untuk memasak cepat saat camping.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/mawarkotak.jpeg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Kompor Grill',
            'slug' => Str::slug(md5('Kompor Grill')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Kompor khusus untuk memanggang daging, sayuran, dan makanan lainnya. Nyaman untuk acara BBQ saat berkemah.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/grill.jpg'),
            'rent_price' => 20000,
        ]);

        Item::create([
            'name' => 'Cookng Set DS 308',
            'slug' => Str::slug(md5('Cookng Set DS 308')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Satu set peralatan masak ringkas untuk outdoor, model DS 308. Terdiri dari panci, wajan, dan aksesoris lainnya.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/DS_308.jpeg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Grill Pan',
            'slug' => Str::slug(md5('Grill Pan')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Wajan panggangan khusus untuk memasak daging dan ikan. Permukaan anti lengket dan mudah dibersihkan.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/grillpan.jpg'),
            'rent_price' => 15000,
        ]);

        Item::create([
            'name' => 'Gas Refil + Kaleng',
            'slug' => Str::slug(md5('Gas Refil + Kaleng')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Paket isi ulang gas lengkap dengan kalengnya. Ideal untuk pengguna kompor portable saat camping.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/gassrefilkaleng.jpeg'),
            'rent_price' => 14000,
        ]);

        Item::create([
            'name' => 'Gas Refil / Tukar Kaleng',
            'slug' => Str::slug(md5('Gas Refil / Tukar Kaleng')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Layanan isi ulang gas atau tukar kaleng kosong untuk efisiensi bahan bakar selama perjalanan outdoor.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/gassrefilltukar.jpeg'),
            'rent_price' => 8000,
        ]);

        Item::create([
            'name' => 'Gelas Carabiner',
            'slug' => Str::slug(md5('Gelas Carabiner')),
            'stock' => 10,
            'id_category' => 3,
            'description' => 'Gelas stainless dengan gantungan carabiner, mudah dibawa dan cocok untuk minuman panas atau dingin saat berkemah.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-masak/carabiner.jpeg'),
            'rent_price' => 5000,
        ]);

        // Perlengkapan Trekking & Survival
        Item::create([
            'name' => 'Sepatu Gunung',
            'slug' => Str::slug(md5('Sepatu Gunung')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Sepatu khusus untuk mendaki gunung dengan daya cengkram kuat dan tahan terhadap medan ekstrem.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/sepatugunung.jpg'),
            'rent_price' => 25000,
        ]);

        Item::create([
            'name' => 'Tas Gunung / Carrier 40L 60L 80L',
            'slug' => Str::slug(md5('Tas Gunung / Carrier 40L 60L 80L')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Tas carrier berbagai ukuran untuk membawa perlengkapan mendaki. Nyaman dan memiliki banyak kompartemen.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/tasgunung.jpeg'),
            'rent_price' => 30000,
        ]);

        Item::create([
            'name' => 'Drybag',
            'slug' => Str::slug(md5('Drybag')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Tas tahan air yang menjaga barang-barang penting tetap kering meski terkena hujan atau terendam air.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/drybag.jpg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Trekking Pole',
            'slug' => Str::slug(md5('Trekking Pole')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Tongkat bantu pendakian untuk menjaga keseimbangan dan mengurangi beban lutut saat trekking.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/trekkingpole.jpg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Topi Rimba',
            'slug' => Str::slug(md5('Topi Rimba')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Topi lebar bergaya rimba yang melindungi kepala dan wajah dari panas matahari dan hujan ringan.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/topirimba.jpeg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Flysheet',
            'slug' => Str::slug(md5('Flysheet')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Lembaran terpal serbaguna untuk pelindung dari hujan dan panas saat camping atau bivak.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/flysheet.jpg'),
            'rent_price' => 15000,
        ]);

        Item::create([
            'name' => 'Tiang Flysheet',
            'slug' => Str::slug(md5('Tiang Flysheet')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Tiang penyangga flysheet agar berdiri stabil. Bisa digunakan di berbagai medan.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/tiangflysheet.jpeg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Tali + Pasak Flysheet (6 Unit)',
            'slug' => Str::slug(md5('Tali + Pasak Flysheet (6 Unit)')),
            'stock' => 10,
            'id_category' => 4,
            'description' => 'Paket tali dan pasak untuk mengikat dan menstabilkan flysheet. Ideal untuk pemasangan yang kokoh.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-trekking_survival/talipasak.jpeg'),
            'rent_price' => 8000,
        ]);

        // Alat Penerangan
        Item::create([
            'name' => 'Headlamp Baterai',
            'slug' => Str::slug(md5('Headlamp Baterai')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Lampu kepala yang menggunakan baterai sebagai sumber daya, praktis untuk aktivitas malam saat mendaki.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/headlamp.jpg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Headlamp Charger',
            'slug' => Str::slug(md5('Headlamp Charger')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Headlamp yang dapat diisi ulang menggunakan charger USB, cocok untuk penggunaan berulang di alam terbuka.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/headlampcharger.jpg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Lampu Tenda Baterai',
            'slug' => Str::slug(md5('Lampu Tenda Baterai')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Lampu penerangan dalam tenda yang ditenagai baterai, memberikan cahaya lembut saat berkemah.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/lamputendabaterai.jpg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Lampu Tenda Charger',
            'slug' => Str::slug(md5('Lampu Tenda Charger')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Lampu gantung tenda yang bisa diisi ulang, hemat dan ramah lingkungan untuk aktivitas luar ruangan.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/lamputendacharger.jpeg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Lampu Tumblr',
            'slug' => Str::slug(md5('Lampu Tumblr')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Lampu dekoratif berwarna-warni yang cocok untuk menciptakan suasana hangat di perkemahan.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/lamputumblr.jpeg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Senter Baterai',
            'slug' => Str::slug(md5('Senter Baterai')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Senter genggam yang menggunakan baterai, ringan dan ideal untuk pencahayaan saat berjalan malam.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/senterbaterai.jpeg'),
            'rent_price' => 5000,
        ]);

        Item::create([
            'name' => 'Senter Charger',
            'slug' => Str::slug(md5('Senter Charger')),
            'stock' => 10,
            'id_category' => 5,
            'description' => 'Senter portabel dengan baterai isi ulang, memberikan cahaya terang dan tahan lama untuk kegiatan outdoor.',
            'is_available' => true,
            'image' => $this->parseImage('alat-penerangan/sentercharger.jpg'),
            'rent_price' => 10000,
        ]);

        // Perlengkapan Lainnya
        Item::create([
            'name' => 'Meja Lipat',
            'slug' => Str::slug(md5('Meja Lipat')),
            'stock' => 10,
            'id_category' => 6,
            'description' => 'Meja lipat portabel yang mudah dibawa dan cocok untuk kegiatan camping atau piknik.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-lainnya/mejalipat.jpeg'),
            'rent_price' => 20000,
        ]);

        Item::create([
            'name' => 'Kursi Lipat',
            'slug' => Str::slug(md5('Kursi Lipat')),
            'stock' => 10,
            'id_category' => 6,
            'description' => 'Kursi lipat ringan dan nyaman, praktis untuk duduk santai selama kegiatan luar ruangan.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-lainnya/kursilipat.jpeg'),
            'rent_price' => 15000,
        ]);

        Item::create([
            'name' => 'Hammock',
            'slug' => Str::slug(md5('Hammock')),
            'stock' => 10,
            'id_category' => 6,
            'description' => 'Tempat tidur gantung yang terbuat dari bahan ringan dan kuat, cocok untuk bersantai di antara dua pohon.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-lainnya/hammock.jpeg'),
            'rent_price' => 10000,
        ]);

        Item::create([
            'name' => 'Sarung Tangan',
            'slug' => Str::slug(md5('Sarung Tangan')),
            'stock' => 10,
            'id_category' => 6,
            'description' => 'Sarung tangan pelindung untuk menjaga tangan tetap hangat dan terlindung saat melakukan aktivitas luar.',
            'is_available' => true,
            'image' => $this->parseImage('perlengkapan-lainnya/sarungtangan.png'),
            'rent_price' => 5000,
        ]);
    }
}
