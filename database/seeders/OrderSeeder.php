<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Data
        $customers = DB::table('users')->where('role', 'customer')->get();
        $shops = DB::table('shops')->get();

        $couriers = ['JNE', 'J&T', 'SiCepat', 'Pos Indonesia'];
        
        $statuses = [
            'pending_payment',
            'pending_confirmation',
            'processing',
            'shipped',
            'completed',
            'cancelled'
        ];

        // Definisi komentar ulasan Bahasa Indonesia yang realistis
        $comments = [
            'Sangat memuaskan! Kualitas produk sangat baik, pengemasan rapi dan aman.',
            'Barang cepat sampai, kurirnya ramah. Kualitas produk jempolan!',
            'Produknya asli Indonesia banget, bagus sekali bahannya.',
            'Harga terjangkau tapi kualitas premium. Sukses terus UMKM Indonesia!',
            'Recommended seller! Proses pesanan cepat, produk dikemas tebal.',
            'Rasanya enak sekali, bumbu pas, keluarga di rumah suka.',
            'Jahitannya rapi, batiknya halus, warnanya awet tidak luntur.',
            'Ukirannya halus sekali, pengerjaan rapi. Sangat cocok buat dekorasi rumah.',
            'Barang mendarat dengan selamat tanpa cacat. Terima kasih seller.',
            'Pelayanan sangat memuaskan, fast response. Mantap!'
        ];

        // Buat 100 Pesanan
        for ($i = 1; $i <= 100; $i++) {
            // Pilih customer acak
            $customer = $customers->random();
            // Pilih toko acak
            $shop = $shops->random();
            // Ambil produk dari toko tersebut
            $products = DB::table('products')->where('shop_id', $shop->id)->get();
            if ($products->isEmpty()) {
                continue;
            }

            // Tentukan status pesanan secara proporsional
            // Indeks $i: 1-10 (pending_payment), 11-20 (pending_confirmation), 21-40 (processing), 41-60 (shipped), 61-90 (completed), 91-100 (cancelled)
            if ($i <= 10) {
                $status = 'pending_payment';
            } elseif ($i <= 20) {
                $status = 'pending_confirmation';
            } elseif ($i <= 40) {
                $status = 'processing';
            } elseif ($i <= 60) {
                $status = 'shipped';
            } elseif ($i <= 90) {
                $status = 'completed';
            } else {
                $status = 'cancelled';
            }

            // Dapatkan alamat default customer
            $address = DB::table('addresses')->where('user_id', $customer->id)->where('is_default', true)->first();
            $shippingAddress = $address ? "{$address->recipient_name} | {$address->phone}\n{$address->address_line}" : "Customer {$customer->name}";

            // Tentukan tanggal pemesanan acak dalam 30 hari terakhir
            $daysAgo = rand(0, 30);
            $orderDate = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Generate nomor invoice
            $invoiceNumber = 'INV/' . $orderDate->format('Ymd') . '/' . Str::upper(Str::random(8));

            // Pilih 1-3 produk acak dari toko tersebut
            $purchasedProducts = $products->random(min(rand(1, 3), $products->count()));
            
            $totalAmount = 0.00;
            $itemsData = [];

            foreach ($purchasedProducts as $product) {
                $qty = rand(1, 2);
                $price = $product->price;
                $subtotal = $qty * $price;
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $shippingCost = rand(1, 3) * 5000 + 5000; // 10000, 15000, 20000
            $grandTotal = $totalAmount + $shippingCost;

            // Simpan Order
            $orderId = DB::table('orders')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'shop_id' => $shop->id,
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => $status,
                'shipping_address' => $shippingAddress,
                'courier' => $couriers[array_rand($couriers)],
                'tracking_number' => in_array($status, ['shipped', 'completed']) ? 'RESI' . rand(100000000, 999999999) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Simpan Rincian Order Items, Mutasi Stok, dan Pengurangan Stok
            foreach ($itemsData as $item) {
                $orderItemId = DB::table('order_items')->insertGetId(array_merge($item, [
                    'order_id' => $orderId,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]));

                // Kelola stok & log mutasi
                if ($status !== 'cancelled') {
                    // Kurangi stok produk
                    DB::table('products')->where('id', $item['product_id'])->decrement('stock', $item['qty']);

                    // Catat mutasi keluar (OUT)
                    DB::table('stock_mutations')->insert([
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'type' => 'OUT',
                        'description' => 'Penjualan - #' . $invoiceNumber,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                } else {
                    // Pesanan dibatalkan: catat mutasi keluar lalu masuk kembali untuk audit lengkap
                    DB::table('stock_mutations')->insert([
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'type' => 'OUT',
                        'description' => 'Penjualan - #' . $invoiceNumber,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);

                    DB::table('stock_mutations')->insert([
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'type' => 'IN',
                        'description' => 'Pembatalan Pesanan - #' . $invoiceNumber,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                // Berikan Review acak untuk pesanan yang completed
                if ($status === 'completed' && rand(0, 1) === 1) {
                    DB::table('reviews')->insert([
                        'product_id' => $item['product_id'],
                        'order_item_id' => $orderItemId,
                        'rating' => rand(4, 5),
                        'comment' => $comments[array_rand($comments)],
                        'created_at' => $orderDate->addMinutes(rand(120, 1440)), // Ulasan dibuat beberapa jam setelah diterima
                        'updated_at' => $orderDate->addMinutes(rand(120, 1440)),
                    ]);
                }
            }

            // Simpan Pembayaran (jika status bukan pending_payment)
            if ($status !== 'pending_payment') {
                $paymentStatus = 'paid';
                if ($status === 'pending_confirmation') {
                    $paymentStatus = 'pending';
                } elseif ($status === 'cancelled') {
                    $paymentStatus = 'failed';
                }

                DB::table('payments')->insert([
                    'order_id' => $orderId,
                    'payment_method' => 'transfer',
                    'payment_status' => $paymentStatus,
                    'amount_paid' => $grandTotal,
                    'proof_of_payment' => $paymentStatus !== 'failed' ? 'proofs/transfer_' . Str::random(10) . '.jpg' : null,
                    'paid_at' => $paymentStatus === 'paid' ? $orderDate->addMinutes(rand(5, 59)) : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }
        }

        // 7. Agregasikan & Seed data Ringkasan Harian (daily_sales_summaries)
        $completedOrders = DB::table('orders')
            ->where('status', 'completed')
            ->get();

        // Kelompokkan pesanan completed berdasarkan shop_id dan tanggal pemesanan
        $summaryGroups = [];
        foreach ($completedOrders as $order) {
            $date = date('Y-m-d', strtotime($order->created_at));
            $key = "{$order->shop_id}_{$date}";

            if (!isset($summaryGroups[$key])) {
                $summaryGroups[$key] = [
                    'shop_id' => $order->shop_id,
                    'date' => $date,
                    'total_orders' => 0,
                    'total_revenue' => 0.00,
                    'total_commission' => 0.00,
                ];
            }

            $summaryGroups[$key]['total_orders'] += 1;
            $summaryGroups[$key]['total_revenue'] += $order->grand_total;
            $summaryGroups[$key]['total_commission'] += $order->grand_total * 0.05; // 5% komisi platform
        }

        foreach ($summaryGroups as $group) {
            DB::table('daily_sales_summaries')->insert(array_merge($group, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
