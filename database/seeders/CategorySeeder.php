<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan & Minuman',
                'sub' => [
                    'Kopi & Teh',
                    'Camilan Tradisional',
                    'Sambal & Bumbu',
                ]
            ],
            [
                'name' => 'Pakaian & Fashion',
                'sub' => [
                    'Batik Tradisional',
                    'Aksesoris Fashion',
                ]
            ],
            [
                'name' => 'Kerajinan Tangan',
                'sub' => [
                    'Dekorasi Kayu & Pahat',
                    'Anyaman & Gerabah',
                ]
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'sub' => [
                    'Herbal & Jamu Tradisional',
                ]
            ],
            [
                'name' => 'Gaya Hidup & Hobi',
                'sub' => []
            ]
        ];

        foreach ($categories as $cat) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cat['sub'] as $subName) {
                DB::table('categories')->insert([
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
