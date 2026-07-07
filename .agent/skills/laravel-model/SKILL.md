---
name: laravel-model
description: Panduan pembuatan dan standarisasi Model Eloquent di Laravel 10 untuk proyek TokoKita.
---

# Panduan Pembuatan Model Eloquent Laravel 10

Skill ini memandu AI Agent dalam merancang, menulis, dan memperbarui berkas Model Eloquent pada direktori `app/Models/` di proyek **TokoKita** agar konsisten dengan spesifikasi database (migrations) dan class diagram.

---

## 1. Struktur Dasar & Konvensi File

1. **Nama Class & File**: Menggunakan nama tunggal (singular) dalam format **PascalCase** (contoh: `User.php`, `StockMutation.php`, `DailySalesSummary.php`).
2. **Namespace**: Selalu gunakan `namespace App\Models;`.
3. **Pemberian Nama Tabel (`$table`)**:
   - Secara default Laravel mengasumsikan nama tabel adalah bentuk jamak (plural snake_case) dari nama model.
   - Untuk tabel dengan nama kustom atau gabungan kata yang berpotensi memiliki pluralisasi berbeda di Laravel, definisikan `$table` secara eksplisit (contoh: `protected $table = 'stock_mutations';`, `protected $table = 'daily_sales_summaries';`).

---

## 2. Penggunaan Trait Standar

Semua Model Eloquent harus mengimplementasikan trait yang relevan sesuai perannya:

1. **`HasFactory`**:
   - Lokasi import: `use Illuminate\Database\Eloquent\Factories\HasFactory;`
   - Digunakan pada semua model untuk keperluan database testing dan seeder.
2. **`SoftDeletes`**:
   - Lokasi import: `use Illuminate\Database\Eloquent\SoftDeletes;`
   - Digunakan **HANYA** pada model yang tabelnya memiliki kolom `deleted_at` di berkas migrasi (misal: `User`, `Shop`, `Product`).

---

## 3. Mass Assignment & Type Casting

1. **`$fillable`**:
   - Definisikan seluruh kolom tabel yang dapat diisi secara massal (mass assignment).
   - Kecualikan primary key (`id`), kolom timestamps (`created_at`, `updated_at`), dan kolom soft deletes (`deleted_at`).
2. **`$hidden`**:
   - Digunakan untuk menyembunyikan kolom sensitif dari representasi JSON/Array (contoh: `password` dan `remember_token` pada `User`).
3. **`$casts`**:
   - Gunakan casting tipe data untuk memastikan integritas dan akurasi nilai saat diakses melalui objek PHP:
     - `integer` untuk foreign key (`*_id`) dan kuantitas (`qty`, `stock`, `weight`).
     - `boolean` untuk status bertipe true/false (`is_active`, `is_default`).
     - `decimal:2` atau `decimal:N` untuk field finansial (uang) dan rating (`price`, `balance`, `rating`, `total_amount`, dll).
     - `datetime` atau `date` untuk timestamps/tanggal khusus (`paid_at`, `date`, `email_verified_at`).

---

## 4. Konvensi Penulisan Relasi (Relationships)

Setiap relasi harus ditulis secara eksplisit dengan **Return Type Declaration** untuk mempermudah auto-complete di IDE dan analisis statis kode:

- **BelongsTo** (Bentuk Tunggal): `: BelongsTo`
  - Contoh: `public function user(): BelongsTo`
- **HasMany** (Bentuk Jamak): `: HasMany`
  - Contoh: `public function products(): HasMany`
- **HasOne** (Bentuk Tunggal): `: HasOne`
  - Contoh: `public function payment(): HasOne`
- **BelongsToMany** (Bentuk Jamak): `: BelongsToMany` (untuk relasi Many-to-Many via pivot)
  - Contoh: `public function categories(): BelongsToMany`

---

## 5. Contoh Pola Penulisan Model

### A. Model Standar dengan Relasi Tunggal & Jamak (Contoh: `Shop`)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'balance',
        'address',
        'city_id',
        'status',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'city_id' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User pemilik toko (1-to-1 inverse).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke daftar produk milik toko (1-to-Many).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

### B. Model Transaksional dengan Kustomisasi Nama Tabel & Casting Tanggal (Contoh: `DailySalesSummary`)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySalesSummary extends Model
{
    use HasFactory;

    // Nama tabel kustom yang didefinisikan secara eksplisit
    protected $table = 'daily_sales_summaries';

    protected $fillable = [
        'shop_id',
        'date',
        'total_orders',
        'total_revenue',
        'total_commission',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'date' => 'date',
        'total_orders' => 'integer',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
    ];

    /**
     * Relasi ke Shop terkait.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
```

### C. Model Many-to-Many dengan Pivot Table (Contoh: `Product`)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'is_active',
        'rating',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
    ];

    /**
     * Relasi Many-to-Many ke Category melalui tabel pivot category_product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * Relasi ke Shop.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
```
