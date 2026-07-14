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

## Tahap 4: Pembuatan Service Class (Service Layer)

Untuk menjaga Controller tetap ramping (thin controller) dan memusatkan logika bisnis, buatlah Service Class di direktori `app/Services/{Entity}Service.php`.

### Aturan Service Class:
1. Hubungkan logika bisnis utama (misal: transaksi database kompleks, interaksi dengan API, kalkulasi, mutasi stok, dll) di dalam Service Class.
2. Gunakan database transaction (`DB::transaction`) jika terdapat beberapa query tulis (create/update/delete) yang saling terkait demi menjaga konsistensi data.
3. Gunakan custom Exception jika terdapat kesalahan logika bisnis (contoh: stok habis, status tidak valid).

### Contoh Template Service:
```php
<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class BrandService
{
    public function createBrand(array $data): Brand
    {
        return DB::transaction(function () use ($data) {
            // Tambahkan logika bisnis di sini jika diperlukan
            return Brand::create($data);
        });
    }

    public function updateBrand(Brand $brand, array $data): Brand
    {
        return DB::transaction(function () use ($brand, $data) {
            $brand->update($data);
            return $brand;
        });
    }

    public function deleteBrand(Brand $brand): void
    {
        DB::transaction(function () use ($brand) {
            // Reassign or delete related children/models
            $brand->products()->delete();
            $brand->delete();
        });
    }
}
```

---

## Tahap 5: Pembuatan Resource Controller

Buat berkas controller di `app/Http/Controllers/{Entity}Controller.php`.

### Aturan Controller:
1. Gunakan method standard Resource: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
2. Gunakan dependency injection untuk menginjeksikan Service Class terkait di constructor atau langsung pada method parameter.
3. Hindari menulis logika manipulasi database langsung di Controller; delegasikan ke Service Class.
4. Gunakan dependency injection untuk custom Form Request.
5. Selalu tambahkan otorisasi (misal: `$this->authorize('view', $item)` atau via middleware di rute) untuk menjaga keamanan.
6. Mengembalikan view atau redirect dengan pesan sukses: `->with('success', 'Data berhasil disimpan.')`.

### Contoh Template Controller dengan Service:
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): View
    {
        $brands = Brand::latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $this->brandService->createBrand($request->validated());

        return redirect()->route('admin.brands.index')
            ->with('success', 'Kategori merek berhasil ditambahkan.');
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $this->brandService->updateBrand($brand, $request->validated());

        return redirect()->route('admin.brands.index')
            ->with('success', 'Kategori merek berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->brandService->deleteBrand($brand);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Kategori merek berhasil dihapus.');
    }
}
```

---

## Tahap 6: Registrasi Route

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

## Tahap 7: Pembuatan View CRUD (Blade + Tailwind)

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
