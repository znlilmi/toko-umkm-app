<x-app-layout>
    <div class="py-6 max-w-xl mx-auto space-y-6">
        <div>
            <a href="{{ route('merchant.products.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Produk</span>
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-2">Tambah Produk Baru</h1>
            <p class="text-xs text-slate-400 mb-6">Lengkapi data produk untuk mulai memasarkannya secara publik.</p>

            <form action="{{ route('merchant.products.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('name')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-2">Slug Produk (URL Unik)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('slug')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea name="description" id="description" rows="4" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm" placeholder="Jelaskan spesifikasi, keunggulan, bahan, atau ukuran produk..."></textarea>
                    @error('description')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-slate-700 mb-2">Harga Jual (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('price')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('stock')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-semibold text-slate-700 mb-2">Berat Produk (gram)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}" required min="1" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('weight')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Categories Multiple Select -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kategori Produk (Bisa pilih lebih dari satu)</label>
                    <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-white">
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-2 p-1.5 hover:bg-slate-50 rounded-lg cursor-pointer text-sm">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-600 font-medium">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category_ids')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ms-2 block text-sm font-medium text-slate-700">Aktifkan produk langsung ke katalog publik</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                    Simpan Produk
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
