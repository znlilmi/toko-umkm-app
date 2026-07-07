<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Tentukan kota dan detail alamat berdasarkan email atau role
            if ($user->role === 'admin') {
                continue;
            }

            $recipientName = $user->name;
            $phone = $user->phone;
            $isDefault = true;

            if ($user->email === 'budi@merchant.com') {
                $addressLine = 'Jl. Dharmahusada No. 12';
                $cityId = 444; // Surabaya
            } elseif ($user->email === 'siti@merchant.com') {
                $addressLine = 'Jl. Slamet Riyadi No. 45';
                $cityId = 445; // Surakarta
            } elseif ($user->email === 'agus@merchant.com') {
                $addressLine = 'Jl. Raya Ubud No. 88';
                $cityId = 128; // Gianyar
            } else {
                // Untuk Customer
                $recipientName = $user->name;
                $phone = $user->phone;
                
                // Variasi alamat pembeli
                $addresses = [
                    ['line' => 'Jl. Kebon Jeruk Baru No. 15, Jakarta Barat', 'city' => 151], // Jakarta Barat
                    ['line' => 'Jl. Dago Asri No. 4, Bandung', 'city' => 23], // Bandung
                    ['line' => 'Jl. Kaliurang KM 5, Yogyakarta', 'city' => 501], // Yogyakarta
                    ['line' => 'Jl. Gajah Mada No. 200, Semarang', 'city' => 399], // Semarang
                    ['line' => 'Jl. Jenderal Sudirman No. 18, Medan', 'city' => 278], // Medan
                ];

                // Pilih acak dari daftar alamat
                $selected = $addresses[$user->id % count($addresses)];
                $addressLine = $selected['line'];
                $cityId = $selected['city'];
            }

            DB::table('addresses')->insert([
                'user_id' => $user->id,
                'recipient_name' => $recipientName,
                'phone' => $phone,
                'address_line' => $addressLine,
                'city_id' => $cityId,
                'is_default' => $isDefault,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Untuk customer, beri alamat kedua yang opsional (tidak default)
            if ($user->role === 'customer') {
                DB::table('addresses')->insert([
                    'user_id' => $user->id,
                    'recipient_name' => $recipientName . ' (Kantor)',
                    'phone' => $phone,
                    'address_line' => 'Gedung Cyber, Lantai ' . (($user->id % 10) + 1) . ', Jl. Kuningan Barat, Jakarta Selatan',
                    'city_id' => 153, // Jakarta Selatan
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
