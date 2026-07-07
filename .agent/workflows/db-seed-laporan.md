# Workflow: Pengisian Ringkasan Laporan Harian (daily_sales_summaries)

Workflow ini memandu langkah-langkah untuk menghitung data penjualan harian secara berkala dan mengisi tabel [daily_sales_summaries](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000013_create_daily_sales_summaries_and_indexes.php) menggunakan data historis yang sudah ada pada tabel [orders](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000008_create_orders_table.php) dan [order_items](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000009_create_order_items_table.php).

---

## Langkah 1: Memahami Tujuan Pengisian Ringkasan
Untuk menghindari kueri langsung yang lambat ke tabel [orders](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000008_create_orders_table.php) dan [order_items](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000009_create_order_items_table.php) setiap kali merchant memuat dashboard, data agregasi penjualan harian disimpan ke tabel [daily_sales_summaries](file:///c:/laragon/www/toko-umkm-app/database/migrations/2026_07_07_000013_create_daily_sales_summaries_and_indexes.php).

Ringkasan ini dihitung berdasarkan kriteria berikut:
1. Hanya memproses pesanan dengan status `'completed'`.
2. Dikelompokkan per toko (`shop_id`) dan per hari (`DATE(created_at)`).
3. Kolom yang diisi:
   - `total_orders`: Jumlah transaksi/pesanan sukses unik per hari.
   - `total_revenue`: Total nilai transaksi (`grand_total`) per hari.
   - `total_commission`: Total potongan komisi platform (5% dari `grand_total`) per hari.

---

## Langkah 2: Menjalankan Pengisian Melalui Query SQL Murni (MySQL)

Anda dapat menjalankan query SQL berikut langsung pada MySQL CLI, phpMyAdmin, atau GUI database clients lainnya untuk memproses agregasi secara instan:

```sql
INSERT INTO daily_sales_summaries (shop_id, date, total_orders, total_revenue, total_commission, created_at, updated_at)
SELECT 
    o.shop_id,
    DATE(o.created_at) as date,
    COUNT(DISTINCT o.id) as total_orders,
    SUM(o.grand_total) as total_revenue,
    SUM(o.grand_total * 0.05) as total_commission,
    NOW() as created_at,
    NOW() as updated_at
FROM orders o
INNER JOIN (
    -- Subquery untuk memastikan relasi integritas dengan order_items terverifikasi
    SELECT order_id 
    FROM order_items 
    GROUP BY order_id
) oi ON o.id = oi.order_id
WHERE o.status = 'completed'
GROUP BY o.shop_id, DATE(o.created_at)
ON DUPLICATE KEY UPDATE 
    total_orders = VALUES(total_orders),
    total_revenue = VALUES(total_revenue),
    total_commission = VALUES(total_commission),
    updated_at = NOW();
```

---

## Langkah 3: Menjalankan Pengisian Melalui Perintah Laravel Tinker

Jika Anda ingin menjalankan proses seeding agregasi ini langsung melalui command line Laravel, gunakan perintah Laravel Tinker berikut:

```bash
php artisan tinker --execute="
$completedOrders = DB::table('orders')
    ->where('status', 'completed')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('order_items')
              ->whereColumn('order_items.order_id', 'orders.id');
    })
    ->get();

$summaryGroups = [];
foreach ($completedOrders as $order) {
    $date = date('Y-m-d', strtotime($order->created_at));
    $key = \"{$order->shop_id}_{$date}\";

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
    $summaryGroups[$key]['total_commission'] += $order->grand_total * 0.05;
}

foreach ($summaryGroups as $group) {
    DB::table('daily_sales_summaries')->updateOrInsert(
        ['shop_id' => $group['shop_id'], 'date' => $group['date']],
        array_merge($group, [
            'created_at' => now(),
            'updated_at' => now(),
        ])
    );
}
echo 'Proses seeding laporan berhasil dijalankan!' . PHP_EOL;
"
```

---

## Langkah 4: Verifikasi Hasil Pengisian

Jalankan perintah berikut menggunakan Tinker untuk memverifikasi apakah data ringkasan harian telah berhasil diisi dan menampilkan total data yang tersimpan:

```bash
php artisan tinker --execute="echo 'Total data daily_sales_summaries: ' . DB::table('daily_sales_summaries')->count() . PHP_EOL;"
```
