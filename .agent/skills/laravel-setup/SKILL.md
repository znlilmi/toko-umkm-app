---
name: laravel-setup
description: Panduan instalasi dan setup awal proyek Laravel 10 untuk TokoKita, termasuk prasyarat PHP, package wajib, konfigurasi database, dan autentikasi Breeze (Blade + Tailwind).
---

# Panduan Setup dan Instalasi Laravel 10 - TokoKita

Skill ini memandu proses instalasi, konfigurasi, dan setup awal proyek berbasis Laravel 10 untuk aplikasi **TokoKita**. Panduan ini mencakup persyaratan sistem, paket wajib, konfigurasi database, serta instalasi sistem autentikasi menggunakan Laravel Breeze dengan stack Blade + Tailwind CSS.

---

## 1. Prasyarat Sistem & Versi PHP

Untuk menjalankan Laravel 10 secara optimal, pastikan lingkungan server atau mesin lokal memenuhi persyaratan minimum berikut:

- **PHP Version**: Minimum **PHP 8.1** (Maksimum PHP 8.3 direkomendasikan).
- **Ekstensi PHP Wajib**:
  - `BCMath`
  - `Ctype`
  - `cURL`
  - `DOM`
  - `Fileinfo`
  - `Filter`
  - `Hash`
  - `Mbstring`
  - `OpenSSL`
  - `PCRE`
  - `PDO` (khususnya driver `pdo_mysql` untuk MySQL)
  - `Session`
  - `Tokenizer`
  - `XML`

---

## 2. Instalasi Proyek & Package Wajib

### A. Instalasi Fresh Laravel 10
Untuk membuat proyek baru menggunakan Laravel 10, jalankan perintah Composer berikut pada terminal:
```bash
composer create-project laravel/laravel:^10.0 tokokita-app
```

### B. Package yang Wajib Diinstal
Setelah proyek berhasil dibuat, beberapa package berikut wajib ditambahkan untuk mendukung pengembangan fitur TokoKita:

1. **Laravel Breeze** (Autentikasi & Scaffolding):
   ```bash
   composer require laravel/breeze --dev
   ```
2. **Laravel Tinker** (Interaksi CLI dengan Database - bawaan Laravel):
   ```bash
   composer require laravel/tinker
   ```
3. **FakerPHP** (Untuk pembuatan dummy data di seeders/factories - bawaan dev):
   ```bash
   composer require fakerphp/faker --dev
   ```

---

## 3. Konfigurasi Lingkungan (`.env`)

Sesuaikan berkas `.env` untuk menghubungkan aplikasi ke basis data MySQL `tokokita`. Cari bagian konfigurasi database dan ubah nilainya menjadi seperti berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokokita
DB_USERNAME=root
DB_PASSWORD=
```

> [!IMPORTANT]
> - Pastikan server database MySQL (seperti MySQL di Laragon atau XAMPP) sudah berjalan.
> - Buat database kosong bernama `tokokita` melalui phpMyAdmin, MySQL CLI, atau admin tool lainnya sebelum menjalankan migrasi.

---

## 4. Setup Autentikasi Laravel Breeze (Blade + Tailwind CSS)

Laravel Breeze menyediakan scaffolding autentikasi yang minimal dan sederhana dengan Tailwind CSS. Ikuti langkah-langkah berikut untuk setup:

### Langkah 1: Instalasi Scaffolding Breeze
Jalankan perintah berikut untuk menginisialisasi setup Breeze dengan stack **Blade + Tailwind CSS**:
```bash
php artisan breeze:install blade
```
*Catatan: Saat proses instalasi berlangsung, pilih opsi default atau sesuai instruksi interaktif (opsi default biasanya menyertakan Tailwind CSS secara otomatis).*

### Langkah 2: Instalasi Dependensi Node & Build Aset
Breeze menggunakan Vite untuk melakukan kompilasi aset CSS (Tailwind) dan JavaScript. Jalankan perintah berikut untuk menginstal dan menjalankan server pengembangan Vite:
```bash
# Instal modul node
npm install

# Jalankan server pengembangan Vite untuk live reload
npm run dev
```

### Langkah 3: Menjalankan Migrasi Database
Setelah konfigurasi `.env` selesai dan tabel autentikasi dari Breeze telah di-generate, jalankan migrasi untuk membuat tabel di basis data `tokokita`:
```bash
php artisan migrate
```

---

## 5. Verifikasi Setup

Untuk memverifikasi bahwa setup telah berhasil:
1. Jalankan server lokal Laravel (jika tidak menggunakan virtual host Laragon):
   ```bash
   php artisan serve
   ```
2. Buka peramban (browser) dan akses `http://127.0.0.1:8000`.
3. Pastikan di sudut kanan atas halaman selamat datang Laravel terdapat tombol **Log in** dan **Register**.
4. Klik tombol **Register** dan daftarkan akun baru untuk memastikan integrasi autentikasi Breeze berjalan lancar dengan database `tokokita`.
