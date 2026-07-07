# Workflow: Database Reset & Seed Verification

Workflow ini digunakan untuk membersihkan database, menjalankan ulang migrasi dari awal, mengisi database dengan data dummy realistis (seeding), dan memverifikasi kesuksesan proses tersebut dengan menghitung jumlah record pada setiap tabel.

---

## Langkah 1: Persiapan Lingkungan (Pre-requisites)
Pastikan berkas konfigurasi `.env` Anda sudah terkonfigurasi dengan benar (terutama variabel `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`), dan server database MySQL Anda sudah aktif.

---

## Langkah 2: Jalankan Fresh Migration & Seeding
Jalankan perintah berikut pada terminal di direktori root proyek untuk menghapus semua tabel lama, membuat struktur tabel baru, dan menjalankan semua seeder:

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan mengeksekusi semua migrasi di `database/migrations/` dan dilanjutkan dengan seeder utama di `DatabaseSeeder.php`.

---

## Langkah 3: Verifikasi Tabel & Hitung Record
Jalankan skrip satu baris berikut menggunakan Laravel Tinker untuk memastikan semua tabel aplikasi telah terbuat di database dan menampilkan jumlah data dummy yang berhasil dibuat:

```bash
php artisan tinker --execute="foreach(['users', 'addresses', 'shops', 'categories', 'products', 'category_product', 'carts', 'orders', 'order_items', 'payments', 'reviews', 'stock_mutations', 'daily_sales_summaries', 'wishlists'] as $table) { try { echo sprintf('%-25s : %d records' . PHP_EOL, $table, DB::table($table)->count()); } catch (\Exception $e) { echo sprintf('%-25s : ERROR (Table does not exist)' . PHP_EOL, $table); } }"
```

### Hasil Verifikasi yang Diharapkan (Minimal):
* `users` : $\ge 14$ records (1 admin + 3 merchants + 10 customers)
* `addresses` : $\ge 23$ records
* `shops` : $\ge 3$ records (1 per merchant)
* `categories` : $\ge 12$ records (kategori + sub-kategori)
* `products` : $\ge 50$ records
* `category_product` : $\ge 100$ records (pivot many-to-many)
* `carts` : $0$ records (keranjang awal kosong sebelum belanja aktif)
* `orders` : $\ge 100$ records
* `order_items` : $\ge 100$ records
* `payments` : $\ge 90$ records (semua pesanan selain `pending_payment`)
* `reviews` : Ulasan acak dari pesanan berstatus `completed`
* `stock_mutations` : Log mutasi masuk dan keluar (dari stock awal + penjualan)
* `daily_sales_summaries` : Agregasi ringkasan harian berdasarkan pesanan selesai
* `wishlists` : $0$ records (wishlist awal kosong sebelum diisi pengguna)

