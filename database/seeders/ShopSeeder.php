<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID merchant
        $budiId = DB::table('users')->where('email', 'budi@merchant.com')->value('id');
        $sitiId = DB::table('users')->where('email', 'siti@merchant.com')->value('id');
        $agusId = DB::table('users')->where('email', 'agus@merchant.com')->value('id');

        $shops = [
            [
                'user_id' => $budiId,
                'name' => 'Toko Budi Jaya',
                'slug' => 'toko-budi-jaya',
                'description' => 'Menyediakan aneka jajanan dan makanan ringan tradisional Indonesia berkualitas.',
                'balance' => 1500000.00,
                'address' => 'Jl. Dharmahusada No. 12, Surabaya',
                'city_id' => 444, // Surabaya City ID
                'status' => 'active',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $sitiId,
                'name' => 'Batik Rahma',
                'slug' => 'batik-rahma',
                'description' => 'Produksi batik tulis dan cap khas Solo dengan bahan premium.',
                'balance' => 3500000.00,
                'address' => 'Jl. Slamet Riyadi No. 45, Surakarta',
                'city_id' => 445, // Surakarta City ID
                'status' => 'active',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $agusId,
                'name' => 'Agus Kerajinan Kayu',
                'slug' => 'agus-kerajinan-kayu',
                'description' => 'Produsen kerajinan pahat kayu estetik dan dekorasi rumah ramah lingkungan.',
                'balance' => 2000000.00,
                'address' => 'Jl. Raya Ubud No. 88, Gianyar, Bali',
                'city_id' => 128, // Gianyar City ID
                'status' => 'active',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('shops')->insert($shops);
    }
}
