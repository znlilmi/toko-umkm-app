<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Shop ID
        $budiShop = DB::table('shops')->where('slug', 'toko-budi-jaya')->first();
        $rahmaShop = DB::table('shops')->where('slug', 'batik-rahma')->first();
        $agusShop = DB::table('shops')->where('slug', 'agus-kerajinan-kayu')->first();

        // Ambil Category ID
        $catKopi = DB::table('categories')->where('slug', 'kopi-teh')->value('id');
        $catCamilan = DB::table('categories')->where('slug', 'camilan-tradisional')->value('id');
        $catSambal = DB::table('categories')->where('slug', 'sambal-bumbu')->value('id');
        $parentFood = DB::table('categories')->where('slug', 'makanan-minuman')->value('id');

        $catBatik = DB::table('categories')->where('slug', 'batik-tradisional')->value('id');
        $catAksesoris = DB::table('categories')->where('slug', 'aksesoris-fashion')->value('id');
        $parentFashion = DB::table('categories')->where('slug', 'pakaian-fashion')->value('id');

        $catPahat = DB::table('categories')->where('slug', 'dekorasi-kayu-pahat')->value('id');
        $catAnyaman = DB::table('categories')->where('slug', 'anyaman-gerabah')->value('id');
        $parentCraft = DB::table('categories')->where('slug', 'kerajinan-tangan')->value('id');

        // 1. Produk Toko Budi Jaya (Makanan & Minuman)
        $foodProducts = [
            ['name' => 'Kopi Arabika Gayo Premium 250g', 'price' => 85000, 'stock' => 50, 'weight' => 280, 'sub_cat' => $catKopi],
            ['name' => 'Kopi Robusta Temanggung 250g', 'price' => 45000, 'stock' => 100, 'weight' => 280, 'sub_cat' => $catKopi],
            ['name' => 'Teh Melati Keraton Bag 100g', 'price' => 15000, 'stock' => 120, 'weight' => 120, 'sub_cat' => $catKopi],
            ['name' => 'Keripik Tempe Renyah Gurih 200g', 'price' => 18000, 'stock' => 75, 'weight' => 230, 'sub_cat' => $catCamilan],
            ['name' => 'Keripik Singkong Pedas Manis 250g', 'price' => 16500, 'stock' => 60, 'weight' => 280, 'sub_cat' => $catCamilan],
            ['name' => 'Sambal Bawang Bu Rudy Asli', 'price' => 29000, 'stock' => 40, 'weight' => 180, 'sub_cat' => $catSambal],
            ['name' => 'Sambal Ijo Bebek Madura Botol', 'price' => 25000, 'stock' => 30, 'weight' => 180, 'sub_cat' => $catSambal],
            ['name' => 'Madu Hutan Sumbawa Murni 350ml', 'price' => 110000, 'stock' => 25, 'weight' => 400, 'sub_cat' => $catCamilan], // Jamu/herbal alternative
            ['name' => 'Kacang Disko Khas Bali 500g', 'price' => 38000, 'stock' => 80, 'weight' => 520, 'sub_cat' => $catCamilan],
            ['name' => 'Bakpia Pathok Khas Jogja Isi 20', 'price' => 40000, 'stock' => 3, 'weight' => 450, 'sub_cat' => $catCamilan], // Stok kritis sengaja
            ['name' => 'Lapis Legit Surabaya Spesial', 'price' => 150000, 'stock' => 15, 'weight' => 1100, 'sub_cat' => $catCamilan],
            ['name' => 'Bumbu Pecel Madiun Pedas 250g', 'price' => 22000, 'stock' => 50, 'weight' => 270, 'sub_cat' => $catSambal],
            ['name' => 'Wedang Uwuh Rempah Instan isi 10', 'price' => 35000, 'stock' => 4, 'weight' => 150, 'sub_cat' => $catKopi], // Stok kritis sengaja
            ['name' => 'Kremes Ayam Kampung Renyah', 'price' => 20000, 'stock' => 45, 'weight' => 150, 'sub_cat' => $catCamilan],
            ['name' => 'Kue Semprong Tradisional Wijen', 'price' => 25000, 'stock' => 30, 'weight' => 300, 'sub_cat' => $catCamilan],
            ['name' => 'Abon Sapi Solo Asli Manis 250g', 'price' => 75000, 'stock' => 35, 'weight' => 270, 'sub_cat' => $catCamilan],
            ['name' => 'Emping Melinjo Super Tipis 500g', 'price' => 48000, 'stock' => 50, 'weight' => 550, 'sub_cat' => $catCamilan],
            ['name' => 'Cireng Rujak Instan Bumbu Rujak', 'price' => 15000, 'stock' => 2, 'weight' => 300, 'sub_cat' => $catCamilan], // Stok kritis sengaja
        ];

        // 2. Produk Batik Rahma (Pakaian & Fashion)
        $fashionProducts = [
            ['name' => 'Kemeja Batik Tulis Sutra Solo', 'price' => 850000, 'stock' => 5, 'weight' => 250, 'sub_cat' => $catBatik],
            ['name' => 'Kemeja Batik Cap Katun Prima', 'price' => 185000, 'stock' => 20, 'weight' => 220, 'sub_cat' => $catBatik],
            ['name' => 'Daster Ruffle Batik Cap Busui', 'price' => 95000, 'stock' => 40, 'weight' => 200, 'sub_cat' => $catBatik],
            ['name' => 'Selendang Sutra Batik Cantik', 'price' => 350000, 'stock' => 8, 'weight' => 100, 'sub_cat' => $catBatik],
            ['name' => 'Outer Vest Batik Parang Etnik', 'price' => 145000, 'stock' => 15, 'weight' => 180, 'sub_cat' => $catBatik],
            ['name' => 'Sarung Batik Pekalongan Eksklusif', 'price' => 125000, 'stock' => 30, 'weight' => 300, 'sub_cat' => $catBatik],
            ['name' => 'Blouse Batik Sogogan Modern', 'price' => 160000, 'stock' => 12, 'weight' => 210, 'sub_cat' => $catBatik],
            ['name' => 'Baju Koko Batik Slimfit Kombinasi', 'price' => 175000, 'stock' => 25, 'weight' => 230, 'sub_cat' => $catBatik],
            ['name' => 'Celana Kulot Batik Cap Santai', 'price' => 110000, 'stock' => 18, 'weight' => 280, 'sub_cat' => $catBatik],
            ['name' => 'Tas Jinjing Kain Perca Batik', 'price' => 45000, 'stock' => 50, 'weight' => 150, 'sub_cat' => $catAksesoris],
            ['name' => 'Masker Kain Batik 3ply Earloop', 'price' => 7500, 'stock' => 200, 'weight' => 15, 'sub_cat' => $catAksesoris],
            ['name' => 'Peci Rajut Motif Batik Klasik', 'price' => 35000, 'stock' => 35, 'weight' => 80, 'sub_cat' => $catAksesoris],
            ['name' => 'Scarf Batik Printing Motif Megamendung', 'price' => 60000, 'stock' => 30, 'weight' => 90, 'sub_cat' => $catAksesoris],
            ['name' => 'Rok Lilit Batik Instan All Size', 'price' => 98000, 'stock' => 22, 'weight' => 250, 'sub_cat' => $catBatik],
            ['name' => 'Kain Batik Meteran Motif Kawung', 'price' => 55000, 'stock' => 100, 'weight' => 200, 'sub_cat' => $catBatik],
            ['name' => 'Blazer Outer Etnik Tenun Jepara', 'price' => 295000, 'stock' => 10, 'weight' => 350, 'sub_cat' => $catBatik],
        ];

        // 3. Produk Agus Kerajinan Kayu (Kerajinan Tangan)
        $craftProducts = [
            ['name' => 'Talenan Kayu Jati Solid Estetik', 'price' => 75000, 'stock' => 30, 'weight' => 600, 'sub_cat' => $catPahat],
            ['name' => 'Mangkok Kayu Jati Set Sendok Garpu', 'price' => 95000, 'stock' => 25, 'weight' => 400, 'sub_cat' => $catPahat],
            ['name' => 'Tatakan Gelas Kayu Pinus Set Isi 6', 'price' => 45000, 'stock' => 40, 'weight' => 350, 'sub_cat' => $catPahat],
            ['name' => 'Patung Kucing Kayu Hias Set Isi 3', 'price' => 120000, 'stock' => 15, 'weight' => 800, 'sub_cat' => $catPahat],
            ['name' => 'Asbak Kayu Mahoni Ukir Khas Bali', 'price' => 35000, 'stock' => 50, 'weight' => 250, 'sub_cat' => $catPahat],
            ['name' => 'Kotak Tisu Anyaman Rotan Alami', 'price' => 65000, 'stock' => 20, 'weight' => 300, 'sub_cat' => $catAnyaman],
            ['name' => 'Vas Bunga Pahat Kayu Sonokeling', 'price' => 85000, 'stock' => 12, 'weight' => 500, 'sub_cat' => $catPahat],
            ['name' => 'Standing Planter Kayu Hias Tiga Susun', 'price' => 165000, 'stock' => 8, 'weight' => 2200, 'sub_cat' => $catPahat],
            ['name' => 'Kotak Perhiasan Kayu Ukir Kunci Rahasia', 'price' => 195000, 'stock' => 3, 'weight' => 700, 'sub_cat' => $catPahat], // Stok kritis sengaja
            ['name' => 'Lampu Tidur Bambu Etnik Estetik', 'price' => 135000, 'stock' => 10, 'weight' => 900, 'sub_cat' => $catAnyaman],
            ['name' => 'Cobek Batu Gunung Mini Penumbuk Bumbu', 'price' => 50000, 'stock' => 15, 'weight' => 1500, 'sub_cat' => $catAnyaman],
            ['name' => 'Gantungan Kunci Kayu Ukir Custom', 'price' => 8000, 'stock' => 300, 'weight' => 10, 'sub_cat' => $catPahat],
            ['name' => 'Cermin Dinding Bingkai Ukir Jati 40cm', 'price' => 350000, 'stock' => 4, 'weight' => 3200, 'sub_cat' => $catPahat], // Stok kritis sengaja
            ['name' => 'Nampan Saji Rotan Kayu Kombinasi', 'price' => 110000, 'stock' => 15, 'weight' => 750, 'sub_cat' => $catAnyaman],
            ['name' => 'Piring Kayu Pinus Bulat 20cm', 'price' => 32000, 'stock' => 50, 'weight' => 200, 'sub_cat' => $catPahat],
            ['name' => 'Hiasan Dinding Ukiran Motif Daun', 'price' => 90000, 'stock' => 18, 'weight' => 850, 'sub_cat' => $catPahat],
        ];

        // Jalankan import per shop
        $this->seedProductsForShop($budiShop->id, $foodProducts, $parentFood);
        $this->seedProductsForShop($rahmaShop->id, $fashionProducts, $parentFashion);
        $this->seedProductsForShop($agusShop->id, $craftProducts, $parentCraft);
    }

    private function seedProductsForShop(int $shopId, array $productsList, int $parentCategoryId): void
    {
        foreach ($productsList as $p) {
            // 1. Simpan produk ke tabel products
            $productId = DB::table('products')->insertGetId([
                'shop_id' => $shopId,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']) . '-' . Str::random(5),
                'description' => 'Produk UMKM Indonesia asli: ' . $p['name'] . '. Dibuat dengan bahan baku lokal pilihan berkualitas tinggi untuk mendukung ekonomi lokal.',
                'price' => $p['price'],
                'stock' => $p['stock'],
                'weight' => $p['weight'],
                'is_active' => true,
                'rating' => 4.5 + (($productId ?? 1) % 6) * 0.1, // Rating dinamis di kisaran 4.5 - 5.0
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Hubungkan ke kategori (sub-kategori dan parent kategori) di tabel pivot category_product
            DB::table('category_product')->insert([
                [
                    'category_id' => $p['sub_cat'],
                    'product_id' => $productId,
                ],
                [
                    'category_id' => $parentCategoryId,
                    'product_id' => $productId,
                ]
            ]);

            // 3. Simpan mutasi stok awal di tabel stock_mutations
            DB::table('stock_mutations')->insert([
                'product_id' => $productId,
                'qty' => $p['stock'],
                'type' => 'IN',
                'description' => 'Stok Awal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
