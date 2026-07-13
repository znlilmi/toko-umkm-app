<x-app-layout>
    <div class="py-6 max-w-xl mx-auto">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-2">Pengaturan Profil Toko</h1>
            <p class="text-xs text-slate-400 mb-6">Ubah data identitas toko UMKM Anda yang akan ditampilkan ke publik.</p>

            <form action="{{ route('merchant.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Shop Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Toko</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $shop->name) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('name')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-2">Slug Toko (URL Unik)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $shop->slug) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('slug')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Toko</label>
                    <textarea name="description" id="description" rows="3" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">{{ old('description', $shop->description) }}</textarea>
                    @error('description')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Toko / Asal Pengiriman</label>
                    <textarea name="address" id="address" rows="3" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">{{ old('address', $shop->address) }}</textarea>
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
                            <option value="{{ $id }}" {{ old('city_id', $shop->city_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Current Logo Preview & New Upload -->
                <div>
                    <label for="logo" class="block text-sm font-semibold text-slate-700 mb-2">Logo Toko (max 2MB)</label>
                    @if($shop->logo)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo Toko" class="w-16 h-16 rounded-xl object-cover border">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-2.5">
                    @error('logo')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Current Banner Preview & New Upload -->
                <div>
                    <label for="banner" class="block text-sm font-semibold text-slate-700 mb-2">Banner Toko (max 4MB)</label>
                    @if($shop->banner)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $shop->banner) }}" alt="Banner Toko" class="w-full h-24 rounded-xl object-cover border">
                        </div>
                    @endif
                    <input type="file" name="banner" id="banner" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-2.5">
                    @error('banner')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                    Perbarui Toko
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
