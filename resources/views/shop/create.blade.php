<x-app-layout>
    <div class="py-6 max-w-xl mx-auto">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-2">Buka Toko Gratis</h1>
            <p class="text-xs text-slate-400 mb-6">Mulai langkah sukses bisnis Anda dengan mendaftarkan toko UMKM Anda di TokoKita.</p>

            <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Shop Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Toko</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('name')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-2">Slug Toko (URL Unik)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('slug')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Toko</label>
                    <textarea name="description" id="description" rows="3" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm" placeholder="Ceritakan keistimewaan dan kategori produk toko Anda..."></textarea>
                    @error('description')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Toko / Asal Pengiriman</label>
                    <textarea name="address" id="address" rows="3" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm" placeholder="Alamat lengkap lokasi pengiriman produk..."></textarea>
                    @error('address')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- City ID -->
                <div>
                    <label for="city_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kota Asal</label>
                    <select name="city_id" id="city_id" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm bg-white">
                        <option value="">-- Pilih Kota --</option>
                        @foreach(config('cities') as $id => $name)
                            <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-semibold text-slate-700 mb-2">Logo Toko (max 2MB)</label>
                    <input type="file" name="logo" id="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-2.5">
                    @error('logo')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Banner -->
                <div>
                    <label for="banner" class="block text-sm font-semibold text-slate-700 mb-2">Banner Toko (max 4MB)</label>
                    <input type="file" name="banner" id="banner" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-2.5">
                    @error('banner')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                    Daftarkan Toko Saya
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
