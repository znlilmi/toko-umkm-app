# Batasan Ruang Lingkup (Scope) Aplikasi TokoKita

Dokumen ini mendefinisikan ruang lingkup (scope) pengerjaan aplikasi **TokoKita** untuk kebutuhan Tugas Akhir / Skripsi selama 1 semester. Fokus utama dari batasan ini adalah memastikan sistem memiliki bobot akademis yang cukup (melalui modul pengelolaan yang kompleks) dan menyediakan analisis data yang kaya (melalui minimal 10 jenis laporan dalam berbagai format).

---

## 1. Modul Pengelolaan Kompleks (Core Academic Weight)

Untuk memenuhi standar kelayakan skripsi, sistem ini tidak hanya mengimplementasikan CRUD (Create, Read, Update, Delete) sederhana, melainkan berfokus pada:

### **Modul Transaksi Terdistribusi (Split-Order Checkout) & Sistem Mutasi Stok Terintegrasi**

Modul ini memiliki kompleksitas tinggi karena menangani:
1. **Multi-Shop Checkout Splitter:**
   - Pembeli dapat memasukkan produk dari beberapa toko (merchant) berbeda ke dalam keranjang belanja secara bersamaan.
   - Saat checkout, sistem secara otomatis memecah (split) keranjang menjadi beberapa nomor pesanan (sub-order/invoice) berdasarkan toko masing-masing.
   - Ongkos kirim dihitung secara terpisah untuk setiap toko ke alamat pembeli menggunakan data berat produk dan koordinat/alamat toko masing-masing.
2. **State Machine Siklus Pesanan (Order Lifecycle State Machine):**
   - Transaksi dikontrol secara ketat menggunakan state transition logic untuk menghindari inkonsistensi status pembayaran dan pengiriman.
   - Status transisi: `Pending Payment` &rarr; `Verifying Payment` &rarr; `Processing` &rarr; `Shipped` &rarr; `Completed` ATAU `Cancelled`.
3. **Mekanisme Penguncian Stok & Rollback (Stock Locking & Inventory Ledger):**
   - **Pencegahan Over-selling:** Menggunakan transaksi database (`DB::transaction` dan `SELECT ... FOR UPDATE` di MySQL) untuk mengunci stok produk secara real-time saat pembeli menekan tombol bayar.
   - **Otomatisasi Rollback (Scheduler/Cron):** Jika pembayaran tidak diselesaikan dalam batas waktu (misal: 1 jam), sistem otomatis membatalkan pesanan dan mengembalikan stok produk (*restock*) ke inventaris toko melalui log mutasi stok.
   - **Log Mutasi Stok (Stock Ledger/Audit Trail):** Setiap perubahan stok tidak langsung mengubah kolom `stock` begitu saja, tetapi dicatat pada tabel `stock_mutations` dengan tipe `IN` (restock, pembatalan), `OUT` (penjualan), dan `ADJUSTMENT` (koreksi stok rusak/salah input).

---

## 2. Rincian 10 Jenis Laporan (Format: Dashboard, Grafik, PDF, & Excel)

Sistem harus memproduksi minimal 10 jenis laporan berikut untuk membantu Merchant mengelola operasional toko dan Admin memantau platform:

### A. Format Dashboard & Grafik (Real-time Visual Analytics)
1. **Laporan 1: Dashboard Ringkasan Finansial Toko (Merchant Dashboard)**
   - **Tampilan:** Widget kartu (KPI Cards) di halaman utama dashboard merchant.
   - **Metrik:** Total Pendapatan Kotor, Jumlah Transaksi Sukses, Rata-rata Nilai Pesanan (AOV), dan Saldo Siap Tarik (Withdrawal).
   - **Tujuan:** Memberikan gambaran cepat kondisi finansial toko tanpa harus membuka laporan detail.
2. **Laporan 2: Grafik Tren Penjualan Harian/Bulanan (Interactive Chart)**
   - **Tampilan:** Grafik garis (Line Chart) / batang (Bar Chart) menggunakan *Chart.js* atau *ApexCharts*.
   - **Metrik:** Volume GMV (Gross Merchandise Value) dan frekuensi transaksi dalam rentang waktu yang dipilih (7 hari terakhir, 30 hari terakhir, atau kustom).
   - **Tujuan:** Menganalisis tren naik-turun penjualan dari waktu ke waktu.
3. **Laporan 3: Grafik Distribusi Kategori Produk Terjual (Pie/Donut Chart)**
   - **Tampilan:** Grafik lingkaran (Pie/Donut Chart).
   - **Metrik:** Persentase kontribusi penjualan per kategori produk.
   - **Tujuan:** Membantu pemilik toko melihat kategori produk apa yang paling diminati oleh pembeli.

### B. Format Dokumen Cetak / PDF (Formal & Document Friendly)
4. **Laporan 4: Invoice Transaksi Pembelian (PDF/Print)**
   - **Tampilan:** Dokumen berformat PDF yang bersih dan profesional (menggunakan package `barryvdh/laravel-dompdf`).
   - **Metrik:** Nomor invoice, tanggal transaksi, detail pembeli, rincian produk (nama, qty, subtotal), biaya ongkir, potongan diskon (jika ada), total bayar, dan status pembayaran.
   - **Tujuan:** Digunakan oleh merchant sebagai bukti transaksi sekaligus label pengiriman untuk ditempel di paket fisik.
5. **Laporan 5: Laporan Detail Transaksi Penjualan Berkala (PDF)**
   - **Tampilan:** Dokumen PDF berupa tabel kompilasi penjualan berdasarkan rentang tanggal tertentu (Bulanan/Tahunan).
   - **Metrik:** Tanggal, No. Invoice, Nama Pembeli, Jumlah Item, Total Belanja, Ongkos Kirim, Kurir Ekspedisi, dan Metode Pembayaran.
   - **Tujuan:** Arsip fisik bulanan merchant untuk keperluan pelaporan pajak atau pembukuan luar jaringan.
6. **Laporan 6: Laporan Produk Stok Kritis (PDF)**
   - **Tampilan:** Tabel PDF berisi daftar produk yang stoknya berada di bawah batas minimum (*low-stock threshold*).
   - **Metrik:** Kode SKU/ID Produk, Nama Produk, Kategori, Stok Tersisa, Batas Minimum Stok, dan Nama Supplier (jika ada).
   - **Tujuan:** Panduan cepat bagi bagian pengadaan (purchasing) untuk segera melakukan *restock* produk.

### C. Format Spreadsheet / Excel (Data Processing Friendly)
7. **Laporan 7: Laporan Rekap Penjualan untuk Akuntansi (Excel - XLSX)**
   - **Tampilan:** Unduhan file Excel terformat (menggunakan package `maatwebsite/excel`).
   - **Metrik:** Kolom detail transaksi lengkap termasuk Harga Pokok Penjualan (HPP), Harga Jual, Laba Kotor per transaksi, dan status pajak platform.
   - **Tujuan:** Memudahkan merchant memproses data keuangan lebih lanjut menggunakan rumus akuntansi Excel (Pivot Table, VLOOKUP, dll).
8. **Laporan 8: Kartu Mutasi Stok Produk & Audit Trail (Excel - XLSX)**
   - **Tampilan:** Log kronologis pergerakan stok suatu produk tertentu dalam format Excel.
   - **Metrik:** Waktu Kejadian, ID Produk, Nama Produk, Stok Awal, Kuantitas Perubahan (+ / -), Stok Akhir, Keterangan Mutasi (e.g., "Penjualan Invoice #INV-102", "Restock manual", "Retur barang cacat").
   - **Tujuan:** Menghindari kecurangan (fraud) internal toko dan melacak selisih stok fisik vs sistem.
9. **Laporan 9: Laporan Ulasan & Kepuasan Pelanggan (Excel - XLSX)**
   - **Tampilan:** Rekap data ulasan produk dari pelanggan.
   - **Metrik:** Tanggal Ulasan, Nama Produk, Rating (1-5), Komentar/Ulasan Pelanggan, dan Status Respons Merchant.
   - **Tujuan:** Bahan evaluasi tim *customer service* dan tim produksi untuk meningkatkan kualitas produk.

### D. Laporan Konsolidasi Global (Khusus Administrator Platform)
10. **Laporan 10: Laporan Performa Merchant & Komisi Platform (Dashboard & PDF)**
    - **Tampilan:** Halaman dashboard admin global dengan opsi ekspor ke PDF.
    - **Metrik:** Nama Toko, Pemilik, Tanggal Bergabung, Total Transaksi Sukses (GMV Toko), Persentase Potongan Komisi (Platform Fee), dan Total Komisi yang Diterima Platform.
    - **Tujuan:** Memantau toko paling aktif serta menghitung pendapatan internal platform TokoKita secara keseluruhan.

---

## 3. Batasan Pengerjaan (In-Scope vs. Out-of-Scope)

Untuk menjamin proyek selesai tepat waktu dalam waktu 1 semester (sekitar 4-6 bulan efektif), batasan pengerjaan ditetapkan sebagai berikut:

### **Di Luar Scope (Out-of-Scope) - TIDAK Dikerjakan:**
- **Integrasi Kurir Logistik Asli (Real API RajaOngkir Pro):** Simulasi ongkos kirim akan menggunakan data statis/lokal (hardcoded berdasarkan kota asal-tujuan) untuk menghindari dependensi API berbayar atau API limit.
- **Payment Gateway Live Production:** Menggunakan simulasi/sandbox mode (atau transfer manual dengan upload bukti transfer) sehingga tidak memerlukan verifikasi badan hukum/bisnis resmi ke pihak Midtrans/Xendit.
- **Aplikasi Mobile (Android/iOS):** Aplikasi hanya berbasis Web Responsive (dapat diakses via mobile browser dengan baik, namun tidak dicompile menjadi APK/IPA).
- **Rekomendasi Produk berbasis Machine Learning:** Pencarian dan rekomendasi produk menggunakan pencarian teks biasa (`LIKE %query%` di SQL) atau filter kategori standar.

### **Di Dalam Scope (In-Scope) - HARUS Dikerjakan:**
- Modul Multi-Merchant (Registrasi User, Pendaftaran Toko, Dashboard Merchant).
- Modul Manajemen Produk (CRUD, Upload Gambar, Kategori, Limitasi Stok).
- Keranjang Belanja & Split Checkout (Pemesanan dari banyak toko sekaligus).
- Sistem Pembayaran (Upload bukti transfer manual & verifikasi status pembayaran).
- State Machine Pemrosesan Pesanan (Input resi pengiriman, konfirmasi barang diterima).
- Modul Pelaporan (Dashboard visualisasi, Ekspor PDF, Ekspor Excel).
- Sistem Keamanan Dasar (Autentikasi, Otorisasi Spatie Permission / Gate Laravel, Proteksi Input).

---

## 4. Matriks Keselarasan Laporan (Report Matrix Overview)

| No | Nama Laporan | Format | Pengguna Utama | Sumber Tabel Database |
| :-: | :--- | :---: | :---: | :--- |
| 1 | Dashboard Finansial | Dashboard | Merchant | `orders`, `payments` |
| 2 | Grafik Tren Penjualan | Grafik | Merchant | `orders` (grouped by date) |
| 3 | Grafik Kategori Terlaris | Grafik | Merchant | `order_items`, `products`, `categories` |
| 4 | Invoice Transaksi | PDF / Print | Pembeli & Merchant | `orders`, `order_items`, `users` |
| 5 | Rekap Penjualan Berkala | PDF | Merchant | `orders`, `payments` |
| 6 | Produk Stok Kritis | PDF | Merchant | `products` (where stock < threshold) |
| 7 | Rekap Penjualan Finansial | Excel | Merchant | `orders`, `order_items`, `products` |
| 8 | Mutasi Stok (Audit Trail) | Excel | Merchant | `stock_mutations`, `products` |
| 9 | Ulasan & Rating Produk | Excel | Merchant | `reviews`, `order_items`, `products` |
| 10| Laporan Performa & Komisi| Dashboard/PDF | Admin Platform | `orders`, `shops`, `users` |
