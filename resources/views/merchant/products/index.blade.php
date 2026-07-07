<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Katalog Produk Toko</h1>
                <p class="text-sm text-slate-400">Kelola daftar katalog produk yang Anda pasarkan secara publik.</p>
            </div>
            <a href="{{ route('merchant.products.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm shadow-indigo-100 transition">
                Tambah Produk Baru
            </a>
        </div>

        <!-- Products table list -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($products->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-700 mb-1">Katalog Masih Kosong</h3>
                    <p class="text-slate-400 text-sm mb-4">Mulai tambahkan produk perdana Anda untuk mulai berjualan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4 text-right">Harga</th>
                                <th class="px-6 py-4 text-center">Stok</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <!-- Name & Weight -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm mb-0.5">{{ $product->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">Berat: {{ $product->weight }} gram</div>
                                    </td>
                                    <!-- Category -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($product->categories as $category)
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-semibold">{{ $category->name }}</span>
                                            @empty
                                                <span class="text-[10px] bg-slate-100 text-slate-400 px-2 py-0.5 rounded font-semibold italic">Tanpa Kategori</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <!-- Price -->
                                    <td class="px-6 py-4 text-right font-extrabold text-slate-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <!-- Stock -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-semibold text-xs {{ $product->stock > 5 ? 'text-slate-700' : ($product->stock > 0 ? 'text-amber-600 font-bold' : 'text-rose-500 font-bold') }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </td>
                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('merchant.products.edit', $product->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition border border-indigo-100">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('merchant.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition border border-rose-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
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
