<x-app-layout>
    <div class="py-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-8 flex items-center space-x-2">
            <svg class="w-8 h-8 text-rose-500 fill-current" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <span>Wishlist Saya</span>
        </h1>

        @if($wishlistItems->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <h3 class="text-xl font-bold text-slate-700 mb-1">Wishlist Masih Kosong</h3>
                <p class="text-slate-400 text-sm mb-6">Simpan barang-barang favorit Anda di sini agar mudah memantau harga dan membelinya nanti.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition">
                    Cari Produk Favorit
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                        <!-- Image -->
                        <div class="aspect-square bg-slate-50 relative overflow-hidden flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                            </svg>
                            <!-- Delete button -->
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-white/80 hover:bg-white text-rose-500 rounded-lg shadow-sm border border-slate-100 transition duration-150">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                            @if($product->stock <= 0)
                                <div class="absolute bottom-3 left-3 bg-rose-500 text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                                    Habis
                                </div>
                            @endif
                        </div>

                        <!-- Info details -->
                        <div class="p-5 flex-1 flex flex-col">
                            <span class="text-[9px] uppercase font-bold text-slate-400 block mb-1">Toko: {{ $product->shop->name }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" class="block font-semibold text-slate-700 hover:text-indigo-600 transition leading-snug line-clamp-2 mb-2">
                                {{ $product->name }}
                            </a>
                            <span class="font-extrabold text-slate-800 text-base mb-4 mt-auto">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                            <!-- Cart add action button -->
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-xs font-semibold rounded-xl transition flex items-center justify-center space-x-1.5 shadow-sm shadow-indigo-50">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span>Masukkan Keranjang</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
