<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary
                            {--date= : Tanggal spesifik untuk agregasi data (YYYY-MM-DD)}
                            {--all : Hitung ulang semua data historis dari awal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengagregasi data penjualan harian (omzet, jumlah pesanan, komisi) per toko';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dateOption = $this->option('date');
        $allOption = $this->option('all');

        $dates = [];

        if ($allOption) {
            $this->info('Menghitung ulang data ringkasan harian untuk seluruh tanggal historis...');
            
            // Ambil semua tanggal unik dari pesanan yang berstatus completed
            $orderDates = DB::table('orders')
                ->where('status', 'completed')
                ->selectRaw('DISTINCT DATE(created_at) as order_date')
                ->pluck('order_date')
                ->toArray();
            
            $dates = $orderDates;
        } elseif ($dateOption) {
            // Validasi format tanggal
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOption)) {
                $this->error('Format tanggal tidak valid. Gunakan format YYYY-MM-DD.');
                return Command::FAILURE;
            }
            $this->info("Mengagregasi data untuk tanggal spesifik: {$dateOption}...");
            $dates = [$dateOption];
        } else {
            // Default: Kemarin dan Hari ini
            $yesterday = Carbon::yesterday()->toDateString();
            $today = Carbon::today()->toDateString();
            $this->info("Mengagregasi data untuk kemarin ({$yesterday}) dan hari ini ({$today})...");
            $dates = [$yesterday, $today];
        }

        if (empty($dates)) {
            $this->warn('Tidak ada data tanggal transaksi yang ditemukan untuk diproses.');
            return Command::SUCCESS;
        }

        // Urutkan tanggal secara kronologis
        sort($dates);

        $processedCount = 0;

        foreach ($dates as $date) {
            $this->comment("Memproses data penjualan untuk tanggal: {$date}...");

            // Kueri pesanan berstatus 'completed' pada tanggal tersebut
            $orders = DB::table('orders')
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('order_items')
                        ->whereColumn('order_items.order_id', 'orders.id');
                })
                ->get();

            // Pengelompokan data berdasarkan shop_id
            $groups = [];
            foreach ($orders as $order) {
                if (!isset($groups[$order->shop_id])) {
                    $groups[$order->shop_id] = [
                        'shop_id' => $order->shop_id,
                        'date' => $date,
                        'total_orders' => 0,
                        'total_revenue' => 0.00,
                        'total_commission' => 0.00,
                    ];
                }
                $groups[$order->shop_id]['total_orders'] += 1;
                $groups[$order->shop_id]['total_revenue'] += $order->grand_total;
                $groups[$order->shop_id]['total_commission'] += $order->grand_total * 0.05; // Komisi platform 5%
            }

            // Masukkan atau perbarui data ke tabel daily_sales_summaries
            foreach ($groups as $shopId => $data) {
                $existing = DB::table('daily_sales_summaries')
                    ->where('shop_id', $shopId)
                    ->where('date', $date)
                    ->first();

                if ($existing) {
                    DB::table('daily_sales_summaries')
                        ->where('id', $existing->id)
                        ->update([
                            'total_orders' => $data['total_orders'],
                            'total_revenue' => $data['total_revenue'],
                            'total_commission' => $data['total_commission'],
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('daily_sales_summaries')->insert([
                        'shop_id' => $shopId,
                        'date' => $date,
                        'total_orders' => $data['total_orders'],
                        'total_revenue' => $data['total_revenue'],
                        'total_commission' => $data['total_commission'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $processedCount++;
            }

            // Bersihkan data lama jika pesanan statusnya berubah dari completed menjadi yang lain
            $existingShopIdsWithSummaries = DB::table('daily_sales_summaries')
                ->where('date', $date)
                ->pluck('shop_id')
                ->toArray();

            $activeShopIds = array_keys($groups);
            $orphanShopIds = array_diff($existingShopIdsWithSummaries, $activeShopIds);

            if (!empty($orphanShopIds)) {
                DB::table('daily_sales_summaries')
                    ->where('date', $date)
                    ->whereIn('shop_id', $orphanShopIds)
                    ->delete();
                
                $this->info("Menghapus data ringkasan kosong untuk tanggal {$date} pada shop_id: " . implode(', ', $orphanShopIds));
            }
        }

        $this->info("Proses selesai. Berhasil memproses {$processedCount} entri ringkasan toko.");
        return Command::SUCCESS;
    }
}
