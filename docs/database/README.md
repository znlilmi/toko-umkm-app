# Dokumentasi Skema Database TokoKita

Dokumen ini menjelaskan struktur Entity Relationship Diagram (ERD) dan penjelasan relasi antar tabel untuk aplikasi TokoKita, platform e-commerce UMKM berbasis Laravel 10. Berkas DBML (Database Markup Language) utama dapat ditemukan di [erd.dbml](file:///c:/laragon/www/toko-umkm-app/docs/database/erd.dbml) yang dapat diimpor langsung ke [dbdiagram.io](https://dbdiagram.io) untuk visualisasi.

---

## Aturan Konvensi Laravel yang Diterapkan
1. **Pluralized Snake Case**: Nama tabel menggunakan format jamak bahasa Inggris (e.g., `users`, `shops`, `products`, `orders`).
2. **Standard Timestamps**: Setiap tabel memiliki kolom `created_at` dan `updated_at` bertipe `timestamp` untuk mencatat waktu pembuatan dan pembaruan data secara otomatis oleh Laravel Eloquent.
3. **Soft Deletes**: Menggunakan kolom `deleted_at` bertipe `timestamp` pada tabel utama (`users`, `shops`, `products`) guna memungkinkan penghapusan logis tanpa kehilangan data historis penting bagi modul pelaporan akuntansi dan mutasi stok.
4. **Foreign Key Naming**: Hubungan relasi menggunakan nama singular dari tabel tujuan diikuti oleh `_id` (e.g., `user_id`, `shop_id`, `product_id`).

---

## Resolusi Inkonsistensi Diagram
Dalam skema database ini, seluruh inkonsistensi antara diagram kelas dan diagram sekuensial sebelumnya telah diselesaikan secara tuntas:
* **Tabel `carts` Ditambahkan**: Berperan sebagai tabel pivot antara `users` dan `products` untuk mengelola keranjang belanja database-backed pembeli.
* **Kolom `status` pada Tabel `shops` Ditambahkan**: Menggunakan kolom `status` (`pending`, `active`, `rejected`, `suspended`) pada tabel `shops` untuk menangani alur moderasi toko oleh admin secara lengkap. Kolom `is_active` tetap dipertahankan sebagai saklar aktif/non-aktif sementara oleh pemilik toko sendiri.
* **Penghapusan Kolom `address` dari Tabel `users`**: Alamat pengguna secara dinamis dikelola melalui tabel relasional `addresses` (Mendukung banyak alamat per user).
* **Kolom `product_id` pada Tabel `reviews` Ditambahkan**: Ditambahkan kolom `product_id` langsung pada tabel `reviews` selain `order_item_id` (1-to-1) untuk mendukung performa kueri penghitungan rata-rata ulasan produk (`SELECT AVG(rating) FROM reviews WHERE product_id = ?`) tanpa perlu melakukan join tabel `order_items` yang berat.
* **Pencegahan Pelanggaran 1NF untuk Kategori**: Menghapus kolom `category_id` (atau `category_ids` berformat JSON) dari tabel `products` dan menggantinya dengan tabel pivot `category_product` untuk mendukung relasi *many-to-many* antara kategori dan produk secara ternormalisasi.

---

## Penjelasan Relasi Antar Tabel

### 1. Modul Pengguna & Alamat (`users` & `addresses`)
* **Relasi**: `users` (1) <--- (0..*) `addresses`
* **Penjelasan**: Seorang Pengguna (`users`) dapat menyimpan lebih dari satu alamat pengiriman (`addresses`) untuk memfasilitasi alamat rumah, kantor, dll. Kolom `is_default` bertipe boolean ditambahkan pada tabel `addresses` untuk menandai satu alamat utama pilihan pengguna.

### 2. Modul Toko/Merchant (`users` & `shops`)
* **Relasi**: `users` (1) <---> (0..1) `shops` (1-to-1)
* **Penjelasan**: Pengguna yang terdaftar sebagai pemilik toko (`merchant`) hanya diperbolehkan memiliki maksimal satu Toko (`shops`). Keterbatasan ini dijaga dengan menerapkan indeks unik (`unique`) pada kolom `user_id` di tabel `shops`.

### 3. Modul Kategori & Produk (`categories` & `products`)
* **Relasi**: 
  - `categories` (1) <--- (0..*) `categories` (Self-relation / Hierarchy)
  - `categories` (0..*) <---> (0..*) `products` (Many-to-Many via `category_product`)
* **Penjelasan**: 
  - Tabel `categories` memiliki relasi ke dirinya sendiri melalui kolom `parent_id` (boleh bernilai null) untuk mendukung sub-kategori berlapis tanpa batas.
  - Hubungan antara kategori dan produk dirancang sebagai *many-to-many* yang dijembatani oleh tabel pivot `category_product` (menyimpan pasangan `category_id` dan `product_id` dengan primary key komposit). Hal ini mencegah pelanggaran Normalisasi Pertama (1NF) dan memungkinkan produk terdaftar di lebih dari satu kategori sekaligus.

### 4. Modul Katalog Produk Toko (`shops` & `products`)
* **Relasi**: `shops` (1) <--- (0..*) `products`
* **Penjelasan**: Sebuah Toko (`shops`) dapat mempublikasikan banyak produk (`products`). Jika toko dihapus secara logis (*soft delete*), produk-produk di dalamnya juga akan terpengaruh.

### 5. Modul Keranjang Belanja (`users`, `products` & `carts`)
* **Relasi**: 
  - `users` (1) <--- (0..*) `carts`
  - `products` (1) <--- (0..*) `carts`
* **Penjelasan**: Tabel `carts` menjembatani hubungan *many-to-many* antara pembeli dan produk yang ingin dibeli sebelum checkout. Kolom `qty` mencatat jumlah item yang dimasukkan ke keranjang belanja.

### 6. Modul Pesanan/Transaksi (`users`, `shops`, `orders` & `order_items`)
* **Relasi**: 
  - `users` (1) <--- (0..*) `orders` (Sebagai Customer/Pembeli)
  - `shops` (1) <--- (0..*) `orders` (Sebagai Penerima Pesanan)
  - `orders` (1) <--- (1..*) `order_items`
  - `products` (1) <--- (0..*) `order_items`
* **Penjelasan**: 
  - Saat pembeli melakukan checkout dari keranjang belanja yang berisi produk dari beberapa toko berbeda, sistem akan memecah (*split order*) pesanan tersebut menjadi beberapa catatan pesanan di tabel `orders` berdasarkan masing-masing `shop_id`.
  - Kolom `total_amount` mencatat subtotal harga barang belanjaan, `shipping_cost` mencatat ongkir dari kurir pilihan, dan `grand_total` adalah akumulasi pembayaran wajib pesanan tersebut.
  - Setiap Pesanan memiliki rincian item di tabel `order_items` yang menyalin (`snapshot`) harga asli produk (`price`) dan subtotal (`subtotal`) saat checkout untuk melindungi transaksi dari perubahan harga produk di kemudian hari.

### 7. Modul Pembayaran (`orders` & `payments`)
* **Relasi**: `orders` (1) <---> (1) `payments` (1-to-1)
* **Penjelasan**: Setiap pesanan (`orders`) memiliki tepat satu instruksi dan bukti pembayaran (`payments`). Relasi 1-to-1 dijamin dengan indeks unik (`unique`) pada kolom `order_id` di tabel `payments`. Bukti transfer disimpan dalam kolom `proof_of_payment` berupa path berkas gambar.

### 8. Modul Ulasan & Rating (`products`, `order_items` & `reviews`)
* **Relasi**: 
  - `order_items` (1) <---> (1) `reviews` (1-to-1)
  - `products` (1) <--- (0..*) `reviews`
* **Penjelasan**: 
  - Pembeli hanya dapat memberikan satu ulasan per item produk yang dibeli dalam suatu pesanan. Ini divalidasi dengan indeks unik pada `order_item_id` di tabel `reviews`.
  - Kolom `product_id` disertakan langsung pada tabel `reviews` agar sistem dapat dengan cepat menarik ulasan produk serta menghitung rata-rata rating (`rating` bernilai 1 sampai 5) tanpa melalui JOIN bertingkat dari `order_items` ke `products`.

### 9. Modul Log Mutasi Stok (`products` & `stock_mutations`)
* **Relasi**: `products` (1) <--- (0..*) `stock_mutations`
* **Penjelasan**: Setiap aktivitas penambahan atau pengurangan stok (perekaman stok awal, penjualan barang, penyesuaian manual oleh toko, dan pengembalian stok akibat pesanan dibatalkan/dibatalkan oleh penjual) dicatat di tabel `stock_mutations` bertipe `'IN'` atau `'OUT'`. Kolom `qty` mencatat jumlah perubahan stok absolut dan `description` menyimpan alasan mutasi stok untuk audit internal merchant.

### 10. Modul Wishlist (`users`, `products` & `wishlists`)
* **Relasi**: 
  - `users` (1) <--- (0..*) `wishlists`
  - `products` (1) <--- (0..*) `wishlists`
* **Penjelasan**: Tabel `wishlists` menjembatani hubungan *many-to-many* antara pembeli dan produk favorit mereka. Indeks unik komposit diterapkan pada `['user_id', 'product_id']` untuk menjamin keunikan penyimpanan (agar satu produk tidak dimasukkan berkali-kali ke daftar favorit oleh pengguna yang sama).

