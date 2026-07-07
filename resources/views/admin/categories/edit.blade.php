<x-app-layout>
    <div class="py-6 max-w-xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Kategori</span>
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-2">Ubah Data Kategori</h1>
            <p class="text-xs text-slate-400 mb-6">Perbarui detail informasi mengenai kategori global.</p>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('name')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-2">Slug Kategori (URL Unik)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('slug')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Parent Category Selection -->
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori Induk</label>
                    <select name="parent_id" id="parent_id" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm bg-white">
                        <option value="">-- Tanpa Induk (Kategori Utama) --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                    Perbarui Kategori
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
