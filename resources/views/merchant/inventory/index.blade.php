<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Stok & Inventaris</h1>
            <p class="text-sm text-slate-400">Pantau ketersediaan stok produk Anda dan lakukan log mutasi stok.</p>
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
