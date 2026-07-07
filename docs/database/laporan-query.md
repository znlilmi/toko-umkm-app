# Analisis Kebutuhan Kueri Laporan & Optimasi Database

Dokumen ini menganalisis kebutuhan kueri untuk setiap modul pelaporan (baik untuk Merchant maupun Admin) berdasarkan use-case yang didefinisikan, dan merancang optimasi berupa indeks tambahan dan tabel ringkasan (*summary table*).

---

## 1. Analisis Kueri Laporan

### A. Laporan Penjualan Berkala (Merchant - PDF/Excel)
* **Kebutuhan Bisnis**: Penjual melihat ringkasan penjualan, total pendapatan, dan detail pesanan yang sukses dalam rentang tanggal tertentu untuk pembukuan keuangan.
* **Kueri SQL Kasus Terburuk**:
  ```sql
  SELECT * FROM orders 
  WHERE shop_id = ? 
    AND status = 'completed' 
    AND created_at BETWEEN ? AND ?
  ORDER BY created_at DESC;
  ```
* **Kelemahan Tanpa Optimasi**: Kueri melakukan pemindaian baris (*row scan*) skala besar jika merchant memiliki banyak pesanan dengan status selain `completed`.
* **Rekomendasi Optimasi**: Indeks komposit pada `orders(shop_id, status, created_at)`.

### B. Laporan Stok Kritis (Merchant - PDF)
* **Kebutuhan Bisnis**: Penjual membutuhkan daftar produk yang stoknya tersisa sedikit (misalnya $\le 5$ pcs) dan produk tersebut dalam kondisi aktif dijual untuk segera direstock.
* **Kueri SQL Kasus Terburuk**:
  ```sql
  SELECT id, name, stock, price FROM products 
  WHERE shop_id = ? 
    AND is_active = true 
    AND stock <= 5;
  ```
* **Rekomendasi Optimasi**: Indeks komposit pada `products(shop_id, is_active, stock)`.

### C. Ekspor Kartu Mutasi Stok (Merchant - Excel)
* **Kebutuhan Bisnis**: Penjual melacak riwayat aliran stok masuk (`IN`) dan keluar (`OUT`) dari suatu produk secara kronologis dalam periode tertentu.
* **Kueri SQL Kasus Terburuk**:
  ```sql
  SELECT * FROM stock_mutations 
  WHERE product_id = ? 
    AND created_at BETWEEN ? AND ? 
  ORDER BY created_at ASC;
  ```
* **Rekomendasi Optimasi**: Indeks komposit pada `stock_mutations(product_id, created_at)`.

### D. Laporan Komisi & Performa Platform (Admin - PDF)
* **Kebutuhan Bisnis**: Administrator memantau total GMV platform, memotong komisi tetap (misalnya 5% dari penjualan), dan menghasilkan laporan performa bulanan secara keseluruhan.
* **Kueri SQL Kasus Terburuk**:
  ```sql
  SELECT o.id, o.grand_total, s.name as shop_name, o.created_at 
  FROM orders o 
  JOIN shops s ON o.shop_id = s.id 
  WHERE o.status = 'completed' 
    AND o.created_at BETWEEN ? AND ?;
  ```
* **Rekomendasi Optimasi**: Indeks komposit pada `orders(status, created_at)`.

---

## 2. Perancangan Tabel Ringkasan (Summary Table)

Untuk mendukung pemuatan grafik tren penjualan harian di dashboard Merchant dan pencarian performa admin secara cepat tanpa perlu terus-menerus memproses jutaan baris data di tabel `orders` dan `order_items`, dirancang tabel ringkasan harian bernama **`daily_sales_summaries`**.

### Spesifikasi Tabel `daily_sales_summaries`
Setiap akhir hari (atau diperbarui secara berkala via event listener/cron job), sistem akan mengagregasi data penjualan per toko dan menyimpannya di tabel ini.

* **Skema Kolom**:
  - `id` (Bigint PK)
  - `shop_id` (Bigint FK, indeks)
  - `date` (Date)
  - `total_orders` (Integer, total transaksi sukses harian)
  - `total_revenue` (Decimal, akumulasi grand_total harian)
  - `total_commission` (Decimal, akumulasi komisi platform harian)
* **Keunikan Constraint**: Indeks unik komposit pada `(shop_id, date)` untuk memastikan tidak ada duplikasi data ringkasan per toko per hari.

---

## 3. Rencana Penerapan Indeks & Skema Baru

Berikut ringkasan indeks tambahan dan tabel baru yang diimplementasikan:

| Tabel | Jenis Optimasi | Kolom | Kegunaan |
| :--- | :--- | :--- | :--- |
| **`daily_sales_summaries`** | Tabel Baru | (Kolom Ringkasan + FK `shop_id`) | Agregasi dashboard trend grafik dan performa |
| **`orders`** | Indeks Komposit | `(shop_id, status, created_at)` | Mempercepat laporan bulanan merchant |
| **`orders`** | Indeks Komposit | `(status, created_at)` | Mempercepat laporan komisi admin |
| **`products`** | Indeks Komposit | `(shop_id, is_active, stock)` | Mempercepat laporan stok menipis/kritis |
| **`stock_mutations`** | Indeks Komposit | `(product_id, created_at)` | Mempercepat pembacaan kartu stok (ledger) |
