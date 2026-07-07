---
name: laravel-migration
description: Panduan pembuatan berkas migrasi database Laravel 10 yang konsisten dengan spesifikasi DBML di docs/database/erd.dbml.
---

# Panduan Pembuatan Migrasi Laravel 10

Skill ini memandu AI Agent dalam menulis berkas migrasi Laravel 10 secara terstruktur, konsisten dengan rancangan ERD di `docs/database/erd.dbml`, dan mengikuti konvensi database terbaik Laravel.

---

## 1. Urutan Pembuatan Tabel (Dependency Order)
Berkas migrasi harus dibuat dan dijalankan dalam urutan yang tepat untuk menghindari error foreign key constraint pada basis data. Urutan eksekusi migrasi yang disarankan adalah:

1. **`users`** (Mandiri)
2. **`addresses`** (Bergantung pada `users`)
3. **`shops`** (Bergantung pada `users`)
4. **`categories`** (Bergantung pada dirinya sendiri via `parent_id`)
5. **`products`** (Bergantung pada `shops`)
6. **`category_product`** (Tabel pivot, bergantung pada `categories` dan `products`)
7. **`carts`** (Bergantung pada `users` dan `products`)
8. **`orders`** (Bergantung pada `users` dan `shops`)
9. **`order_items`** (Bergantung pada `orders` dan `products`)
10. **`payments`** (Bergantung pada `orders`)
11. **`reviews`** (Bergantung pada `products` dan `order_items`)
12. **`stock_mutations`** (Bergantung pada `products`)

---

## 2. Pemetaan Tipe Data MySQL ke Laravel Blueprint
Gunakan tipe data Laravel Blueprint yang sesuai dengan tipe data MySQL dan spesifikasi di DBML:

| Tipe Data di DBML | Method Laravel Blueprint | Contoh Penggunaan |
| :--- | :--- | :--- |
| `bigint pk increment` | `$table->id()` | `$table->id();` |
| `bigint` (Foreign Key) | `$table->foreignId()` | `$table->foreignId('user_id');` |
| `varchar(N)` | `$table->string('name', N)` | `$table->string('slug', 255);` |
| `text` | `$table->text('name')` | `$table->text('description');` |
| `decimal(M, D)` | `$table->decimal('name', M, D)` | `$table->decimal('price', 15, 2);` |
| `integer` | `$table->integer('name')` | `$table->integer('stock');` |
| `boolean` | `$table->boolean('name')` | `$table->boolean('is_active');` |
| `timestamp` | `$table->timestamp('name')` | `$table->timestamp('paid_at');` |
| `datetime` | `$table->dateTime('name')` | `$table->dateTime('expired_at');` |

---

## 3. Aturan Tambahan & Konvensi
1. **Timestamps**: Selalu sertakan `$table->timestamps();` untuk mencatat `created_at` dan `updated_at`.
2. **Soft Deletes**: Berikan `$table->softDeletes();` pada tabel utama (`users`, `shops`, `products`) yang membutuhkannya sesuai dengan spesifikasi ERD.
3. **Foreign Key Constraint**:
   - Selalu tambahkan `->constrained()` untuk foreign key yang mengikuti konvensi penamaan (`table_id`).
   - Gunakan penghapusan kaskade sesuai kebutuhan logika bisnis, misalnya: `->cascadeOnDelete()` atau `->onDelete('cascade')`.
   - Untuk relasi opsional, gunakan `->nullable()->constrained()->nullOnDelete()`.

---

## 4. Contoh Pola Penulisan Migrasi

### A. Pola Tabel Transaksional (Contoh: `orders` & `payments`)
Tabel transaksional memerlukan foreign key yang kuat, penanganan tipe data decimal secara presisi untuk finansial, dan timestamps lengkap.

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            
            // Relasi ke users (sebagai customer)
            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
                  
            // Relasi ke shops (sebagai merchant penerima)
            $table->foreignId('shop_id')
                  ->constrained('shops')
                  ->cascadeOnDelete();
            
            $table->decimal('total_amount', 15, 2);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('grand_total', 15, 2);
            
            $table->string('status')->default('pending_payment');
            $table->text('shipping_address');
            $table->string('courier');
            $table->string('tracking_number')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_payments_table.php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    
    // Relasi 1-to-1 dengan menambahkan unique constraint pada foreignId
    $table->foreignId('order_id')
          ->unique()
          ->constrained('orders')
          ->cascadeOnDelete();
          
    $table->string('payment_method')->default('transfer');
    $table->string('payment_status')->default('pending');
    $table->decimal('amount_paid', 15, 2);
    $table->string('proof_of_payment')->nullable();
    $table->timestamp('paid_at')->nullable();
    
    $table->timestamps();
});
```

### B. Pola Tabel Pivot Many-to-Many (Contoh: `category_product`)
Tabel pivot tidak memerlukan model dan biasanya menggunakan primary key komposit tanpa kolom auto-increment `id` independen, serta tidak memerlukan timestamps kecuali jika eksplisit diinginkan.

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_category_product_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();
                  
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            
            // Primary Key Komposit untuk menjamin keunikan kombinasi baris
            $table->primary(['category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};
```

### C. Pola Tabel dengan Indeks Komposit atau Unik Kustom
Indeks komposit sangat berguna untuk kueri cepat pada multi-kolom filter, sedangkan indeks unik kustom menjamin tidak ada duplikasi data kombinasi.

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_carts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
                  
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();
                  
            $table->integer('qty')->default(1);
            $table->timestamps();

            // Membuat indeks unik komposit agar satu pengguna tidak memiliki 
            // baris terpisah untuk produk yang sama di keranjangnya
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
```
