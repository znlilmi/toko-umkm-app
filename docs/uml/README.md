# Activity Diagrams - TokoKita E-Commerce

Folder ini berisi seluruh Activity Diagram dalam format PlantUML (`.puml`) yang merepresentasikan detail alur proses bisnis untuk masing-masing Use Case dalam sistem **TokoKita**.

---

## Daftar Diagram Aktivitas (Activity Diagrams)

### 1. Modul Autentikasi & Manajemen Pengguna
* **[Registrasi Akun](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-registrasi-akun.puml)**
  - *Aktor:* Pengguna Umum
  - *Deskripsi:* Alur pendaftaran pengguna baru, pengisian detail profile, enkripsi password, dan penyimpanan ke tabel `users`.
* **[Login & Logout](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-login-logout.puml)**
  - *Aktor:* Semua Pengguna
  - *Deskripsi:* Validasi kecocokan email dan hash password, alokasi session/token, pengalihan dashboard berdasarkan role, serta pembersihan session saat logout.
* **[Kelola Profil & Alamat](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-profil.puml)**
  - *Aktor:* Semua Pengguna
  - *Deskripsi:* Pembacaan data user, perubahan biodata, penanganan upload file gambar profil, dan manajemen alamat pengiriman barang.

### 2. Modul Pembeli (Customer)
* **[Cari & Jelajahi Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-cari-produk.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Pencarian katalog produk dengan query teks, filtering kategori/harga, dan visualisasi detail item.
* **[Kelola Keranjang Belanja](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-keranjang.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Manipulasi item belanja (tambah, edit quantity, hapus) dengan validasi real-time ketersediaan stok produk.
* **[Checkout Pesanan (Split Order)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-checkout.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Pemecahan pesanan dari banyak merchant (split-order), kalkulasi jarak pengiriman, transaksi database terisolasi (`SELECT FOR UPDATE`), pengurangan stok otomatis, pembuatan log mutasi stok (`OUT`), dan inisiasi timer kadaluwarsa transaksi.
* **[Unggah Bukti Pembayaran](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-upload-pembayaran.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Upload bukti transfer manual, perekaman detail bank pengirim, dan pembaruan status transaksi ke "Menunggu Konfirmasi".
* **[Lacak Status Pengiriman](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-lacak-pengiriman.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Menelusuri log pergerakan kurir pengiriman menggunakan nomor resi pesanan.
* **[Konfirmasi Terima Barang](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-konfirmasi-terima.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Konfirmasi penerimaan paket fisik, pengalihan dana dari rekening escrow ke saldo bersih merchant, dan pembaruan status pesanan ke "Selesai".
* **[Beri Ulasan & Rating Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-ulasan.puml)**
  - *Aktor:* Pembeli Umum
  - *Deskripsi:* Memberikan umpan balik rating bintang dan ulasan komentar untuk setiap item barang dari transaksi yang sudah sukses, dilanjutkan dengan kalkulasi ulang rata-rata rating produk.
* **[Cetak Invoice Pembelian (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-cetak-invoice.puml)**
  - *Aktor:* Pembeli & Merchant
  - *Deskripsi:* Mengubah tampilan view invoice html menjadi file cetak berekstensi PDF secara real-time.

### 3. Modul Merchant (Penjual)
* **[Pendaftaran & Pengaturan Toko](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-pendaftaran-toko.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Pengajuan pembukaan toko baru, verifikasi nama toko unik, upload logo, banner, dan penentuan alamat pengiriman asal.
* **[Kelola Katalog Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-produk.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Operasi CRUD (Create, Read, Update, Delete) produk, pengaturan harga jual, berat barang, status aktif/tidak aktif, dan inisiasi mutasi stok awal (`IN`).
* **[Kelola Stok & Log Mutasi (Stock Ledger)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-stok.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Melacak histori keluar masuk stok, melakukan penyesuaian stok manual (stock adjustment) secara manual dengan pencatatan audit trail yang ketat di tabel `stock_mutations`.
* **[Kelola Status Pesanan Masuk](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-pesanan-masuk.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Penerimaan bukti transfer pembeli, pengubahan status pesanan ke "Diproses", penginputan nomor resi pengiriman kurir, dan penanganan refund/cancel pesanan dengan rollback stok otomatis.

### 4. Modul Analisis & Pelaporan (Merchant Dashboard)
* **[Lihat Dashboard Finansial & Grafik](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-dashboard-merchant.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Menghitung ringkasan pendapatan kotor/bersih, me-render grafik tren volume transaksi bulanan (Chart.js) dan menyaring peringatan produk yang mendekati kehabisan stok.
* **[Cetak Laporan Penjualan Berkala (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-cetak-laporan-penjualan.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Menyaring data transaksi berdasarkan jangka tanggal tertentu dan mengunduh rekap tabel penjualannya dalam format PDF.
* **[Cetak Laporan Stok Kritis (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-cetak-laporan-stok-kritis.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Menghasilkan daftar produk dengan kuantitas stok di bawah minimum limit dalam format file PDF.
* **[Ekspor Rekap Penjualan Akuntansi (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-ekspor-rekap-penjualan.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Menyediakan file `.xlsx` berisi detail penjualan mencakup Harga Pokok Penjualan (HPP) dan kalkulasi Laba Bersih per transaksi.
* **[Ekspor Kartu Mutasi Stok (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-ekspor-kartu-mutasi.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Ekspor kronologis mutasi keluar masuk stok per produk ke lembar kerja Excel (Audit Trail).
* **[Ekspor Laporan Ulasan (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-ekspor-laporan-ulasan.puml)**
  - *Aktor:* Merchant
  - *Deskripsi:* Mengekspor daftar ulasan, rating, dan komentar pembeli dalam spreadsheet Excel.

### 5. Modul Administrator Platform
* **[Moderasi & Verifikasi Toko](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-verifikasi-toko.puml)**
  - *Aktor:* Admin Platform
  - *Deskripsi:* Menyetujui atau menolak permohonan pembukaan toko baru dari customer dengan memberikan feedback alasan penolakan jika ditolak.
* **[Kelola Kategori Global](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-kelola-kategori-global.puml)**
  - *Aktor:* Admin Platform
  - *Deskripsi:* Menambah, mengubah, dan menghapus kategori utama yang digunakan di platform, lengkap dengan pengecekan relasi produk aktif sebelum penghapusan.
* **[Lihat Dashboard Performa Platform](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-dashboard-admin.puml)**
  - *Aktor:* Admin Platform
  - *Deskripsi:* Menampilkan pertumbuhan merchant bulanan, Gross Merchandise Value (GMV) platform, total komisi terkumpul, dan pemantauan merchant terlaris.
* **[Cetak Laporan Komisi & Performa (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-cetak-laporan-komisi.puml)**
  - *Aktor:* Admin Platform
  - *Deskripsi:* Mengekstrak data komisi platform (potongan transaksi) per merchant ke dalam lembaran laporan formal PDF.

---

## Daftar Diagram Urutan (Sequence Diagrams)

Setiap diagram urutan memetakan swimlane dari diagram aktivitas ke dalam lifelines teknis MVC Laravel (`View`, `Route`, `Controller`, `Model`, `Database`).

### 1. Modul Autentikasi & Manajemen Pengguna
* **[Registrasi Akun](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-registrasi-akun.puml)**
  - *Lifelines:* `Pengguna`, `Blade View`, `Route`, `RegisterController`, `User Model`, `MySQL`
  - *Deskripsi:* Alur validasi, hashing password via bcrypt, query insert, dan inisiasi sesi login.
* **[Login & Logout](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-login-logout.puml)**
  - *Lifelines:* `Pengguna`, `Blade View`, `Route`, `LoginController`, `User Model`, `MySQL`
  - *Deskripsi:* Alur pengecekan hash password, pembuatan session, dan penanganan session invalidation saat logout.
* **[Kelola Profil & Alamat](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-profil.puml)**
  - *Lifelines:* `Pengguna`, `Blade View`, `Route`, `ProfileController`, `User Model`, `MySQL`
  - *Deskripsi:* GET request data profil, update field database, dan handling foto upload.

### 2. Modul Pembeli (Customer)
* **[Cari & Jelajahi Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-cari-produk.puml)**
  - *Lifelines:* `Pembeli`, `Blade View`, `Route`, `ProductController`, `Product Model`, `MySQL`
  - *Deskripsi:* Eksekusi query pencarian kata kunci dengan paginasi dan fetching ulasan produk.
* **[Kelola Keranjang Belanja](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-keranjang.puml)**
  - *Lifelines:* `Pembeli`, `Cart View`, `Route`, `CartController`, `Product Model`, `Cart Model`, `MySQL`
  - *Deskripsi:* Pengecekan stok real-time dan operasi `updateOrCreate` pada tabel `carts`.
* **[Checkout Pesanan (Split Order)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-checkout.puml)**
  - *Lifelines:* `Pembeli`, `Checkout View`, `Route`, `CheckoutController`, `OrderService`, `DB Transaction`, `Product Model`, `Order Model`, `OrderItem Model`, `StockMutation Model`, `MySQL`
  - *Deskripsi:* Pengecekan stok terkunci (`SELECT FOR UPDATE`), multi-merchant order splitting, pengurangan stok, pencatatan log mutasi stok tipe `OUT`, dan DB transactional rollback/commit.
* **[Unggah Bukti Pembayaran](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-upload-pembayaran.puml)**
  - *Lifelines:* `Pembeli`, `Upload View`, `Route`, `PaymentController`, `Payment Model`, `Order Model`, `MySQL`
  - *Deskripsi:* Penyimpanan upload file bukti transfer ke storage dan update order status ke `pending_confirmation`.
* **[Lacak Status Pengiriman](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-lacak-pengiriman.puml)**
  - *Lifelines:* `Pembeli`, `Order Detail View`, `Route`, `OrderController`, `MySQL`
  - *Deskripsi:* Pengambilan resi dan kurir dari order untuk request status tracking.
* **[Konfirmasi Terima Barang](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-konfirmasi-terima.puml)**
  - *Lifelines:* `Pembeli`, `Order Detail View`, `Route`, `OrderController`, `DB Transaction`, `Order Model`, `Payment Model`, `Shop Model`, `MySQL`
  - *Deskripsi:* Database transaction pengubahan status order menjadi `completed`, status bayar menjadi `paid`, dan penambahan saldo toko merchant.
* **[Beri Ulasan & Rating Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-ulasan.puml)**
  - *Lifelines:* `Pembeli`, `Review Modal`, `Route`, `ReviewController`, `Review Model`, `Product Model`, `MySQL`
  - *Deskripsi:* INSERT ulasan dan penghitungan ulang rata-rata rating produk secara otomatis.
* **[Cetak Invoice Pembelian (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-cetak-invoice.puml)**
  - *Lifelines:* `Pengguna`, `Order Detail View`, `Route`, `InvoiceController`, `Order Model`, `Barryvdh DomPDF`, `MySQL`
  - *Deskripsi:* Fetch data relasional lengkap dan streaming output file PDF dari HTML view ke browser.

### 3. Modul Merchant (Penjual)
* **[Pendaftaran & Pengaturan Toko](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-pendaftaran-toko.puml)**
  - *Lifelines:* `Pengguna`, `Open Shop View`, `Route`, `ShopController`, `Shop Model`, `MySQL`
  - *Deskripsi:* Pendaftaran data toko, upload berkas gambar logo, dan generate slug URL unik.
* **[Kelola Katalog Produk](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-produk.puml)**
  - *Lifelines:* `Pemilik Toko`, `Product View`, `Route`, `ProductController`, `Product Model`, `StockMutation Model`, `MySQL`
  - *Deskripsi:* Input CRUD produk dan pencatatan mutasi stok masuk (`IN`) saat barang baru didaftarkan.
* **[Kelola Stok & Log Mutasi (Stock Ledger)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-stok.puml)**
  - *Lifelines:* `Pemilik Toko`, `Inventory View`, `Route`, `InventoryController`, `DB Transaction`, `Product Model`, `StockMutation Model`, `MySQL`
  - *Deskripsi:* Update kuantitas stok di tabel `products` dan INSERT catatan penyesuaian di `stock_mutations` dalam satu transaksi.
* **[Kelola Status Pesanan Masuk](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-pesanan-masuk.puml)**
  - *Lifelines:* `Pemilik Toko`, `Merchant Order View`, `Route`, `MerchantOrderController`, `Order Model`, `DB Transaction`, `Product Model`, `StockMutation Model`, `MySQL`
  - *Deskripsi:* Update status pesanan harian (Proses, Kirim resi, dan Penolakan pesanan palsu dengan pemulihan/rollback stok otomatis).

### 4. Modul Analisis & Pelaporan (Merchant Dashboard)
* **[Lihat Dashboard Finansial & Grafik](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-dashboard-merchant.puml)**
  - *Lifelines:* `Pemilik Toko`, `Seller Dashboard View`, `Route`, `MerchantDashboardController`, `MySQL`
  - *Deskripsi:* Agregasi data omzet dan produk terlaris untuk disajikan ke Chart.js.
* **[Cetak Laporan Penjualan Berkala (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-cetak-laporan-penjualan.puml)**
  - *Lifelines:* `Pemilik Toko`, `Sales Report View`, `Route`, `MerchantReportController`, `Order Model`, `Barryvdh DomPDF`, `MySQL`
  - *Deskripsi:* Query filtering data tanggal dan generate output PDF rekapitulasi penjualan merchant.
* **[Cetak Laporan Stok Kritis (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-cetak-laporan-stok-kritis.puml)**
  - *Lifelines:* `Pemilik Toko`, `Inventory View`, `Route`, `MerchantReportController`, `Product Model`, `Barryvdh DomPDF`, `MySQL`
  - *Deskripsi:* Filter produk stok di bawah ambang batas dan generate PDF stok kritis.
* **[Ekspor Rekap Penjualan Akuntansi (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-ekspor-rekap-penjualan.puml)**
  - *Lifelines:* `Pemilik Toko`, `Sales Report View`, `Route`, `ExportController`, `Order Model`, `Maatwebsite Excel`, `MySQL`
  - *Deskripsi:* Pembuatan worksheet akuntansi berisi detail HPP, Harga Jual, dan margin laba kotor.
* **[Ekspor Kartu Mutasi Stok (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-ekspor-kartu-mutasi.puml)**
  - *Lifelines:* `Pemilik Toko`, `Inventory Logs View`, `Route`, `ExportController`, `StockMutation Model`, `Maatwebsite Excel`, `MySQL`
  - *Deskripsi:* Ekspor data runut pergerakan mutasi masuk/keluar stok ke file spreadsheet Excel.
* **[Ekspor Laporan Ulasan (Excel)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-ekspor-laporan-ulasan.puml)**
  - *Lifelines:* `Pemilik Toko`, `Review List View`, `Route`, `ExportController`, `Review Model`, `Maatwebsite Excel`, `MySQL`
  - *Deskripsi:* Rekapitulasi bintang rating dan ulasan pelanggan toko dalam bentuk file Excel.

### 5. Modul Administrator Platform
* **[Moderasi & Verifikasi Toko](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-verifikasi-toko.puml)**
  - *Lifelines:* `Admin Platform`, `Verification View`, `Route`, `AdminShopController`, `Shop Model`, `MySQL`
  - *Deskripsi:* Update flag `is_active` toko dan dispatching email notifikasi otomatis ke email merchant.
* **[Kelola Kategori Global](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-kelola-kategori-global.puml)**
  - *Lifelines:* `Admin Platform`, `Category View`, `Route`, `AdminCategoryController`, `Category Model`, `Product Model`, `MySQL`
  - *Deskripsi:* Pengecekan dependensi produk aktif sebelum penghapusan record kategori dari basis data.
* **[Lihat Dashboard Performa Platform](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-dashboard-admin.puml)**
  - *Lifelines:* `Admin Platform`, `Admin Dashboard View`, `Route`, `AdminDashboardController`, `MySQL`
  - *Deskripsi:* Pengambilan statistik total users, total toko, volume GMV, dan total komisi platform.
* **[Cetak Laporan Komisi & Performa (PDF)](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-cetak-laporan-komisi.puml)**
  - *Lifelines:* `Admin Platform`, `Admin Finance View`, `Route`, `AdminReportController`, `Order Model`, `Barryvdh DomPDF`, `MySQL`
  - *Deskripsi:* Ekstraksi log potongan komisi platform per toko ke berkas PDF resmi platform.

---

## Diagram Kelas (Class Diagram)

* **[Class Diagram Lengkap](file:///c:/laragon/www/toko-umkm-app/docs/uml/class-diagram.puml)**
  - *Deskripsi:* Sintesis lengkap struktur relasi antarkelas pada platform TokoKita, yang memetakan seluruh model Eloquent ORM (atribut, tipe data, return-type relasi) beserta Controller (action/method API) dan ketergantungan (dependencies) antarkomponen.
