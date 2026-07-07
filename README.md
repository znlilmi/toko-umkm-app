# TokoKita - Platform E-Commerce Multi-Merchant UMKM

TokoKita adalah platform e-commerce multi-merchant yang dirancang khusus untuk mendigitalisasi pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) di Indonesia. Platform ini memungkinkan pemilik UMKM (merchant) membuka toko online secara instan, mengelola katalog produk dan inventaris (stok), serta memantau analitik keuangan bisnis secara real-time. Platform ini dibangun menggunakan **Laravel 10**, **MySQL**, dan **Vite**.

---

## 🚀 Fitur Utama

Sistem TokoKita mengintegrasikan proses e-commerce modern dengan optimasi performa database:

1. **Autentikasi & Manajemen Pengguna (Multi-Role)**
   - Registrasi dan login aman menggunakan enkripsi Bcrypt.
   - Tiga peran pengguna: **Admin Platform**, **Merchant (Penjual)**, dan **Customer (Pembeli)**.
   - Manajemen alamat pengiriman dinamis (mendukung banyak alamat per user, dengan satu alamat default).

2. **Manajemen Toko (Merchant)**
   - Pendaftaran toko mandiri oleh pembeli terdaftar.
   - Pengaturan profil toko (nama, deskripsi, logo, banner, dan alamat asal toko).
   - Status keaktifan toko (buka/tutup sementara) dan sistem moderasi status toko oleh Admin (`pending`, `active`, `rejected`, `suspended`).

3. **Katalog Produk & Manajemen Stok (Stock Ledger)**
   - Struktur kategori hirarkis (parent-child category) dengan relasi pivot *Many-to-Many* antara kategori dan produk.
   - CRUD Produk lengkap dengan multi-upload gambar, berat barang (gram), deskripsi, dan harga.
   - **Audit Trail Stok**: Perekaman kronologis setiap perubahan stok (stok awal, penjualan, restock, penyesuaian manual, pembatalan pesanan) melalui log tabel `stock_mutations` dengan tipe `IN` dan `OUT`.

4. **Transaksi & Alur Pesanan (Order Lifecycle)**
   - Manajemen keranjang belanja (*database-backed shopping cart*).
   - **Split Order Otomatis**: Jika pembeli melakukan checkout dari beberapa toko berbeda, pesanan otomatis dipecah menjadi beberapa invoice berdasarkan masing-masing toko.
   - Simulasi ongkos kirim berdasarkan berat produk dan alamat pengirim-penerima.
   - **Pencegahan Race Condition**: Pengecekan dan pengurangan stok aman menggunakan mekanisme locking (`SELECT FOR UPDATE`) dalam transaksi database (`DB::transaction`).

5. **Pembayaran & Ulasan**
   - Unggah bukti pembayaran/transfer manual oleh pembeli.
   - Konfirmasi pembayaran oleh merchant atau admin untuk mengubah status pesanan.
   - Sistem ulasan dan rating (skala 1-5) per item pesanan yang dibeli dengan penghitungan ulang otomatis rata-rata rating produk.

6. **Dashboard Analitis & Optimasi Kueri Laporan**
   - **Tabel Ringkasan (`daily_sales_summaries`)**: Menghindari kueri berat yang melakukan join jutaan baris data orders saat merchant memuat dashboard grafik tren omzet harian.
   - Komisi platform sebesar 5% dipotong otomatis dari pesanan sukses untuk laporan keuangan Admin.
   - Indeks komposit tambahan pada tabel-tabel utama (`orders`, `products`, `stock_mutations`) untuk mempercepat kueri laporan PDF/Excel.

---

## 📂 Struktur Direktori Proyek

Selain kode aplikasi Laravel standar, proyek ini dilengkapi dengan dokumentasi analisis sistem yang lengkap:

```text
├── .agent/                       # Konfigurasi Rules, Skills, dan Panduan Workflow Agen
│   └── workflows/
│       ├── db-reset.md           # Panduan reset database & verifikasi jumlah data
│       └── db-seed-laporan.md    # Panduan kalkulasi dan seeding ringkasan harian
├── app/                          # Logika Inti Laravel (Controllers, Models, Providers)
├── bootstrap/                    # Berkas Bootstrap Aplikasi
├── config/                       # Konfigurasi Aplikasi Laravel
├── database/
│   ├── factories/                # Pabrik Model (Data Generator)
│   ├── migrations/               # Migrasi Skema Database MySQL (17 Berkas Migrasi Lengkap)
│   └── seeders/                  # Pengisi Data Dummy Realistis (7 Berkas Seeder)
├── docs/                         # DOKUMENTASI SISTEM UTAMA
│   ├── 01-deskripsi-sistem.md    # Analisis Kebutuhan Sistem & Deskripsi Aktor
│   ├── 02-scope-aplikasi.md      # Batasan Lingkup, Spesifikasi Teknis, & Non-Fungsional
│   ├── database/
│   │   ├── README.md             # Penjelasan Relasi Database & Aturan Konvensi Laravel
│   │   ├── erd.dbml              # Database Markup Language untuk visualisasi ERD
│   │   └── laporan-query.md      # Analisis Kueri Laporan & Optimasi Indeks Komposit
│   └── uml/
│       ├── README.md             # Penjelasan 53 Berkas Diagram PlantUML
│       ├── use-case.puml         # Diagram Use Case Sistem
│       ├── class-diagram.puml    # Diagram Kelas (Domain Class Diagram)
│       ├── activity-*.puml       # Diagram Aktivitas untuk setiap alur bisnis
│       └── sequence-*.puml       # Diagram Urutan Lifelines MVC Laravel
├── public/                       # Aset Publik (CSS, JS, Images, Index Entry)
├── resources/                    # View Template (Blade), Assets Mentah (Sass/JS/Lang)
├── routes/                       # Definisi Routing Aplikasi (web, api, console)
├── storage/                      # Berkas Penyimpanan Lokal (Log, Session, Uploads)
├── tests/                        # Automated Testing (Feature & Unit)
└── vite.config.js                # Konfigurasi Vite Asset Bundling
```

---

## 🛠️ Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan komputer Anda telah terinstal:
- **PHP** >= 8.1
- **Composer** (Dependency Manager untuk PHP)
- **MySQL** >= 8.0 (atau MariaDB)
- **Node.js** >= 18 & **NPM**

---

## ⚙️ Langkah Instalasi & Konfigurasi

Ikuti langkah-langkah di bawah ini untuk menyiapkan proyek di lingkungan lokal Anda:

### 1. Kloning Repositori & Masuk ke Direktori
```bash
git clone https://github.com/znlilmi/toko-umkm-app.git
cd toko-umkm-app
```

### 2. Pasang Ketergantungan Composer (Backend)
```bash
composer install
```

### 3. Pasang Ketergantungan NPM & Bangun Aset (Frontend)
```bash
npm install
npm run build
# Atau untuk lingkungan pengembangan aktif:
# npm run dev
```

### 4. Salin & Konfigurasi File Environment
Salin berkas konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_umkm_db  # Pastikan database ini sudah dibuat di MySQL Anda
DB_USERNAME=root          # Username MySQL Anda
DB_PASSWORD=              # Password MySQL Anda
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Reset Database, Jalankan Migrasi & Seeders Utama
Jalankan perintah berikut untuk menghapus database lama (jika ada), membuat struktur tabel baru, dan mengisi database dengan data master realistis (Users, Addresses, Shops, Categories, Products, Orders):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Agregasi Seeding Laporan Harian
Tabel `daily_sales_summaries` tidak diisi secara langsung oleh seeder utama untuk mensimulasikan pekerjaan agregasi terjadwal. Anda harus menjalankannya melalui Laravel Tinker dengan menjalankan perintah satu baris berikut:
```bash
php artisan tinker --execute="
\$completedOrders = DB::table('orders')->where('status', 'completed')->whereExists(function (\$query) { \$query->select(DB::raw(1))->from('order_items')->whereColumn('order_items.order_id', 'orders.id'); })->get();
\$summaryGroups = [];
foreach (\$completedOrders as \$order) {
    \$date = date('Y-m-d', strtotime(\$order->created_at));
    \$key = \"{\$order->shop_id}_{\$date}\";
    if (!isset(\$summaryGroups[\$key])) {
        \$summaryGroups[\$key] = ['shop_id' => \$order->shop_id, 'date' => \$date, 'total_orders' => 0, 'total_revenue' => 0.00, 'total_commission' => 0.00];
    }
    \$summaryGroups[\$key]['total_orders'] += 1;
    \$summaryGroups[\$key]['total_revenue'] += \$order->grand_total;
    \$summaryGroups[\$key]['total_commission'] += \$order->grand_total * 0.05;
}
foreach (\$summaryGroups as \$group) {
    DB::table('daily_sales_summaries')->updateOrInsert(['shop_id' => \$group['shop_id'], 'date' => \$group['date']], array_merge(\$group, ['created_at' => now(), 'updated_at' => now()]));
}
echo 'Proses seeding laporan harian sukses!' . PHP_EOL;
"
```

### 8. Jalankan Verifikasi Pemasangan Database
Pastikan seluruh data berhasil dibuat di database dengan menjalankan kueri verifikasi Tinker berikut:
```bash
php artisan tinker --execute=\"foreach(['users', 'addresses', 'shops', 'categories', 'products', 'category_product', 'carts', 'orders', 'order_items', 'payments', 'reviews', 'stock_mutations', 'daily_sales_summaries', 'wishlists'] as \$table) { try { echo sprintf('%-25s : %d records' . PHP_EOL, \$table, DB::table(\$table)->count()); } catch (\Exception \$e) { echo sprintf('%-25s : ERROR (Table does not exist)' . PHP_EOL, \$table); } }\"
```
**Hasil Verifikasi yang Diharapkan:**
- `users` : $\ge 14$ records (1 admin, 3 merchants, 10 customers)
- `addresses` : $\ge 23$ records
- `shops` : $\ge 3$ records (1 per merchant)
- `categories` : $\ge 12$ records (kategori + sub-kategori)
- `products` : $\ge 50$ records
- `category_product` : $\ge 100$ records (relasi pivot many-to-many)
- `carts` : 0 records (awal)
- `orders` : $\ge 100$ records (riwayat pesanan dummy)
- `order_items` : $\ge 100$ records
- `payments` : $\ge 90$ records
- `reviews` : Ulasan acak dari pesanan selesai
- `stock_mutations` : Log ledger pergerakan stok barang
- `daily_sales_summaries` : Agregasi ringkasan harian
- `wishlists` : 0 records (awal)

### 9. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di alamat [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 📈 Alur Kerja Tambahan

Untuk panduan mendalam tentang alur kerja database dan pengujian laporan, Anda dapat membaca dokumentasi spesifik berikut:
- **[Panduan Database Reset & Verifikasi](file:///.agent/workflows/db-reset.md)**
- **[Panduan Pengisian Laporan Ringkasan Harian](file:///.agent/workflows/db-seed-laporan.md)**
- **[Skema ERD & Aturan Relasi Database](file:///docs/database/README.md)**
- **[Dokumentasi Diagram Alur Bisnis & Sequence UML](file:///docs/uml/README.md)**
