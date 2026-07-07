---
name: generate-crud
description: Langkah-langkah pembuatan modul CRUD lengkap (Migration, Model, Controller, Request, Route, View) yang konsisten dengan standar proyek TokoKita.
---

# Alur Kerja Pembuatan Modul CRUD (generate-crud)

Dokumen ini memandu AI Agent dalam menanggapi permintaan pembuatan modul CRUD baru. Alur kerja ini menerima satu input berupa **Nama Entitas** (misal: `Discount`, `Brand`, `Review`) dan secara otomatis memandu pembuatan 6 komponen utama yang konsisten dengan struktur proyek TokoKita.

---

## Input Parameter
- **Nama Entitas**: Singular PascalCase (contoh: `Brand`)
- **Nama Tabel**: Plural snake_case (contoh: `brands`)

---

## Tahap 1: Pembuatan Migration

Buat berkas migrasi pada direktori `database/migrations/` dengan penamaan `xxxx_xx_xx_xxxxxx_create_{table}_table.php`.

### Aturan Penulisan Migrasi:
1. Selalu tambahkan `$table->id();` dan `$table->timestamps();`.
2. Gunakan `$table->softDeletes();` jika entitas membutuhkan mekanisme soft delete.
3. Tambahkan `->constrained()->cascadeOnDelete()` untuk seluruh foreign key konvensional.
4. Gunakan `decimal('column', 15, 2)` untuk nilai uang atau finansial.
5. Definisikan composite index jika kolom sering di-filter bersama (contoh: `is_active`, `created_at`).

### Contoh Struktur Migration:
```php
Schema::create('brands', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes(); // Optional
});
```

---

## Tahap 2: Pembuatan Model Eloquent

Buat berkas model di `app/Models/{Entity}.php`.

### Aturan Penulisan Model:
1. Pastikan mengimport `HasFactory`.
2. Jika migrasi menggunakan soft deletes, import dan gunakan trait `SoftDeletes`.
3. Definisikan `$fillable` secara eksplisit untuk seluruh input kolom.
4. Definisikan `$casts` untuk ketepatan tipe data di PHP (contoh: `is_active => boolean`, `price => decimal:2`).
5. Deklarasikan tipe kembalian (Return Type Declaration) untuk relasi: `: BelongsTo`, `: HasMany`, `: BelongsToMany`, `: HasOne`.

### Contoh Template Model:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

---

## Tahap 3: Pembuatan Form Request

Buat dua kelas request validasi di `app/Http/Requests/Store{Entity}Request.php` dan `app/Http/Requests/Update{Entity}Request.php`.

### Aturan Validasi:
1. Metode `authorize()` harus mengembalikan nilai `true`.
2. Gunakan `Rule::unique` dengan `ignore` saat memperbarui data (Update) agar tidak memicu validasi error jika nilai slug/email tidak diubah.

### Contoh Store Request:
```php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', 'unique:brands,slug'],
        'description' => ['nullable', 'string'],
        'is_active' => ['sometimes', 'boolean'],
    ];
}
```

---

## Tahap 4: Pembuatan Resource Controller

Buat berkas controller di `app/Http/Controllers/{Entity}Controller.php`.

### Aturan Controller:
1. Gunakan method standard Resource: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
2. Gunakan dependency injection untuk custom Form Request.
3. Selalu tambahkan otorisasi (misal: `$this->authorize('view', $item)` atau via middleware di route) untuk menjaga keamanan.
4. Mengembalikan view atau redirect dengan pesan sukses: `->with('success', 'Data berhasil disimpan.')`.

---

## Tahap 5: Registrasi Route

Daftarkan rute di `routes/web.php`.

### Aturan Registrasi Rute:
1. Masukkan ke dalam grup rute bersarang `middleware(['auth'])`.
2. Gunakan `middleware('role:nama_role')` untuk membatasi hak akses (contoh: `role:admin`, `role:merchant`, atau `role:customer`).
3. Manfaatkan `Route::resource('{url}', {Entity}Controller::class)` untuk simplifikasi.

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('brands', BrandController::class);
});
```

---

## Tahap 6: Pembuatan View CRUD (Blade + Tailwind)

Buat empat berkas Blade di direktori `resources/views/{entities}/`:
1. `index.blade.php`: Halaman daftar data dalam bentuk tabel atau grid yang responsif, lengkap dengan tombol edit, delete, dan pagination.
2. `create.blade.php`: Halaman form tambah data.
3. `edit.blade.php`: Halaman form ubah data dengan data yang sudah terisi.
4. `show.blade.php`: Halaman detail informasi data tunggal.

### Aturan Desain View:
1. Selalu extend master layout dengan membungkus konten menggunakan komponen `<x-app-layout>`.
2. Sediakan navigasi "Kembali" (Back link) pada halaman create, edit, dan show.
3. Tampilkan pesan kesalahan validasi secara lokal menggunakan direktif `@error('nama_field')`.
4. Ikuti skema warna premium (indigo/violet) yang konsisten dengan komponen halaman yang sudah ada.
