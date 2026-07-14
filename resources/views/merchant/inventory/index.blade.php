<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Stok & Inventaris</h1>
                <p class="text-sm text-slate-400">Pantau ketersediaan stok produk Anda dan lakukan log mutasi stok.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('merchant.reports.low-stock-pdf') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-sm shadow-rose-100 transition gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span>Cetak Stok Kritis (PDF)</span>
                </a>
                <a href="{{ route('merchant.reports.stock-mutation-excel') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Ekspor Mutasi Stok (Excel)</span>
                </a>
            </div>
        </div>

        <!-- Inventory List Table -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($products->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <p class="text-sm">Belum ada produk terdaftar untuk kelola stok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4 text-center">Current Stock</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm mb-0.5">{{ $product->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">Berat: {{ $product->weight }} gram</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $product->stock > 5 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($product->stock > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('merchant.inventory.show', $product->id) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                                            Log Mutasi & Sesuaikan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
