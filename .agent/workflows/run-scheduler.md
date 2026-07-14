# Workflow: Pengujian Manual Penjadwalan & Command Summary

Workflow ini memandu langkah-langkah untuk melakukan pengujian manual terhadap command `php artisan summary` serta memverifikasi pendaftaran penjadwalan (scheduler) di platform TokoKita.

---

## Langkah 1: Menjalankan Command Agregasi Secara Langsung

Anda dapat menjalankan command `summary` secara manual untuk memproses data penjualan langsung dari terminal:

### A. Agregasi Standar (Kemarin & Hari Ini)
Menghitung atau memperbarui data penjualan untuk hari kemarin dan hari ini (berguna untuk pembaruan harian rutin):
```bash
php artisan summary
```

### B. Hitung Ulang Semua Data Historis
Menghitung ulang dan menyinkronkan seluruh data transaksi dari awal sejarah data:
```bash
php artisan summary --all
```

### C. Agregasi untuk Tanggal Spesifik
Menghitung data penjualan hanya pada tanggal tertentu (misalnya tanggal 14 Juli 2026):
```bash
php artisan summary --date=2026-07-14
```

---

## Langkah 2: Verifikasi Pendaftaran Jadwal (Schedule List)

Untuk memastikan bahwa command `summary` telah terdaftar dengan benar di scheduler Laravel dan dijadwalkan berjalan setiap hari pukul 01:00 dini hari, jalankan perintah berikut:

```bash
php artisan schedule:list
```

**Hasil yang Diharapkan:**
Akan muncul daftar command yang terjadwal, salah satunya adalah:
```text
0 1 * * *  php artisan summary ................................................ Next Run: [Tanggal] 01:00:00
```

---

## Langkah 3: Menguji Eksekusi Scheduler Secara Manual

Untuk menguji apakah mekanisme scheduler Laravel dapat memanggil command ini dengan sukses tanpa menunggu pukul 01:00 dini hari:

### A. Menggunakan Perintah Interaktif schedule:test (Sangat Direkomendasikan)
Jalankan perintah berikut:
```bash
php artisan schedule:test
```
Pilih nomor indeks yang merujuk pada `php artisan summary` untuk mengeksekusinya langsung melalui konteks scheduler.

### B. Menjalankan Scheduler Run
```bash
php artisan schedule:run
```
*Catatan: Perintah ini hanya akan mengeksekusi jika waktu saat ini tepat pukul 01:00 dini hari (atau menit jatuhnya tempo).*

---

## Langkah 4: Verifikasi Hasil Agregasi di Database

Gunakan Laravel Tinker untuk memastikan data hasil agregasi telah masuk dengan benar ke tabel `daily_sales_summaries`:

### A. Periksa Jumlah Baris Data
```bash
php artisan tinker --execute="echo 'Total baris daily_sales_summaries: ' . DB::table('daily_sales_summaries')->count() . PHP_EOL;"
```

### B. Tampilkan Seluruh Data Ringkasan
```bash
php artisan tinker --execute="print_r(DB::table('daily_sales_summaries')->get()->toArray());"
```
Pastikan kolom `total_orders`, `total_revenue`, dan `total_commission` telah berisi nilai agregasi pesanan yang berstatus `completed` dengan benar.
