<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800 flex items-center space-x-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25g-7.5-4.108-7.5-11.25A7.5 7.5 0 0119.5 10.5z" />
                </svg>
                <span>Daftar Alamat Pengiriman</span>
            </h1>
            <a href="{{ route('addresses.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm shadow-indigo-100 transition">
                Tambah Alamat Baru
            </a>
        </div>

        @if($addresses->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25g-7.5-4.108-7.5-11.25A7.5 7.5 0 0119.5 10.5z" />
                </svg>
                <h3 class="text-lg font-semibold text-slate-700 mb-1">Alamat Belum Terdaftar</h3>
                <p class="text-slate-400 text-sm mb-4">Harap tambahkan alamat pengiriman Anda untuk mempermudah proses checkout pesanan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($addresses as $address)
                    <div class="bg-white border {{ $address->is_default ? 'border-indigo-200 ring-2 ring-indigo-50/50' : 'border-slate-100' }} rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-slate-700 text-base">{{ $address->recipient_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">({{ $address->phone }})</span>
                                @if($address->is_default)
                                    <span class="text-[9px] uppercase font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2 py-0.5 rounded-full">Alamat Utama</span>
                                @endif
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $address->address_line }}</p>
                            <span class="text-xs text-slate-400 block">ID Kota: {{ $address->city_id }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 border-t md:border-t-0 pt-4 md:pt-0 w-full md:w-auto justify-end">
                            <a href="{{ route('addresses.edit', $address->id) }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 rounded-lg transition border border-slate-200">
                                Edit Alamat
                            </a>
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3.5 py-2 text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 rounded-lg transition border border-rose-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
