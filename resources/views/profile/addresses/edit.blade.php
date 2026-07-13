<x-app-layout>
    <div class="py-6 max-w-xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('addresses.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Alamat</span>
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-6">Ubah Alamat Pengiriman</h1>

            <form action="{{ route('addresses.update', $address->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Recipient Name -->
                <div>
                    <label for="recipient_name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Penerima</label>
                    <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('recipient_name')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                    @error('phone')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address Line -->
                <div>
                    <label for="address_line" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                    <textarea name="address_line" id="address_line" rows="3" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">{{ old('address_line', $address->address_line) }}</textarea>
                    @error('address_line')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- City ID -->
                <div>
                    <label for="city_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kota</label>
                    <select name="city_id" id="city_id" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm bg-white">
                        <option value="">-- Pilih Kota --</option>
                        @foreach(config('cities') as $id => $name)
                            <option value="{{ $id }}" {{ old('city_id', $address->city_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Default -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_default" class="ms-2 block text-sm font-medium text-slate-700">Jadikan alamat utama pengiriman</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                    Perbarui Alamat
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
