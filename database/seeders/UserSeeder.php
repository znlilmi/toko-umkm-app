<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin
        DB::table('users')->insert([
            'name' => 'Admin TokoKita',
            'email' => 'admin@tokokita.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Merchants
        $merchants = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@merchant.com',
                'phone' => '081298765432',
                'role' => 'merchant',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@merchant.com',
                'phone' => '085712345678',
                'role' => 'merchant',
            ],
            [
                'name' => 'Agus Dewa',
                'email' => 'agus@merchant.com',
                'phone' => '081399998888',
                'role' => 'merchant',
            ],
        ];

        foreach ($merchants as $m) {
            DB::table('users')->insert(array_merge($m, [
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 3. Seed Customers (10 Pembeli)
        $customers = [
            ['name' => 'Aditya Wijaya', 'email' => 'adit@gmail.com', 'phone' => '081211112222'],
            ['name' => 'Rian Hidayat', 'email' => 'rian@gmail.com', 'phone' => '081233334444'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@gmail.com', 'phone' => '085655556666'],
            ['name' => 'Laras Ayu', 'email' => 'laras@gmail.com', 'phone' => '085777778888'],
            ['name' => 'Fajar Pratama', 'email' => 'fajar@gmail.com', 'phone' => '081999990000'],
            ['name' => 'Eko Sulistyo', 'email' => 'eko@gmail.com', 'phone' => '081122223333'],
            ['name' => 'Sari Indah', 'email' => 'sari@gmail.com', 'phone' => '081244445555'],
            ['name' => 'Mega Utami', 'email' => 'mega@gmail.com', 'phone' => '081366667777'],
            ['name' => 'Roni Setiawan', 'email' => 'roni@gmail.com', 'phone' => '081288889999'],
            ['name' => 'Yudi Anto', 'email' => 'yudi@gmail.com', 'phone' => '085600001111'],
        ];

        foreach ($customers as $c) {
            DB::table('users')->insert(array_merge($c, [
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
