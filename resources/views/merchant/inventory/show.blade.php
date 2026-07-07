<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto space-y-6">
        <div>
            <a href="{{ route('merchant.inventory.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Stok</span>
            </a>
        </div>

        <!-- Product Details & Adjustment Form -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Info Panel -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-2">Informasi Produk</h3>
                    <h1 class="text-xl font-bold text-slate-700 mb-1 leading-snug">{{ $product->name }}</h1>
                    <span class="text-xs text-slate-400">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="mt-6 border-t pt-4 border-slate-100">
                    <span class="text-xs text-slate-400 block mb-1">Stok Saat Ini</span>
                    <span class="text-4xl font-extrabold text-slate-800 block">
                        {{ $product->stock }} <span class="text-sm font-semibold text-slate-400">pcs</span>
                    </span>
                </div>
            </div>

            <!-- Right Form Panel: Adjust stock -->
            <div class="md:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4">Penyesuaian Manual Stok</h3>
                <form action="{{ route('merchant.inventory.adjust', $product->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Adjustment Type -->
                        <div>
                            <label for="type" class="block text-xs font-semibold text-slate-600 mb-1">Tipe Mutasi</label>
                            <select name="type" id="type" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2 text-xs bg-white">
                                <option value="IN">Stok Masuk (+) </option>
                                <option value="OUT">Stok Keluar (-) </option>
                            </select>
                        </div>
                        <!-- Qty -->
                        <div>
                            <label for="qty" class="block text-xs font-semibold text-slate-600 mb-1">Jumlah Unit</label>
                            <input type="number" name="qty" id="qty" required min="1" class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2 text-xs">
                        </div>
                    </div>
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Deskripsi</label>
                        <input type="text" name="description" id="description" placeholder="Contoh: Stok awal, Pembelian suplier, Rusak, dsb." required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2 text-xs">
                    </div>
                    <!-- Submit -->
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg transition shadow-sm">
                        Sesuaikan Stok Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Ledger Table List -->
        <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm p-6">
            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4 border-b pb-3">Riwayat Kartu Mutasi Stok</h3>
            
            @if($mutations->isEmpty())
                <div class="text-center py-8 text-slate-400">
                    <p class="text-sm">Belum ada mutasi stok tercatat untuk produk ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-slate-500">
                        <thead class="text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-4 py-3">Tanggal & Waktu</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-center">Tipe</th>
                                <th class="px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($mutations as $mutation)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3.5">{{ $mutation->created_at->format('d M Y H:i:s') }}</td>
                                    <td class="px-4 py-3.5 text-center font-bold text-slate-700">{{ $mutation->qty }} pcs</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase {{ $mutation->type === 'IN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $mutation->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600">{{ $mutation->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $mutations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
