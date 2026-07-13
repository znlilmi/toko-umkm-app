<x-app-layout>
    <div class="space-y-12 py-6">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-orange-500 via-red-500 to-rose-600 rounded-3xl p-8 md:p-12 text-white shadow-xl shadow-orange-100 flex flex-col md:flex-row justify-between items-center gap-8 border border-orange-400/20">
            <!-- Background Blurs -->
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 space-y-6 max-w-2xl text-center md:text-left">
                <span class="inline-flex items-center text-xs font-bold bg-white/20 text-white px-3.5 py-1.5 rounded-full border border-white/20 uppercase tracking-widest">
                    #BanggaBuatanIndonesia
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-tight">
                    Temukan Produk UMKM Terbaik
                </h1>
                <p class="text-orange-50 text-sm md:text-base leading-relaxed">
                    Dukung pertumbuhan ekonomi lokal dengan berbelanja produk-produk berkualitas tinggi langsung dari produsen UMKM kreatif pilihan di seluruh Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="{{ route('products.index') }}" class="px-6 py-3.5 bg-white text-orange-600 hover:bg-orange-50 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition duration-200 text-center">
                        Mulai Belanja Sekarang
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-6 py-3.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-bold border border-orange-400 hover:border-orange-500 transition duration-200 text-center">
                            Daftar Akun Gratis
                        </a>
                    @endguest
                </div>
            </div>
            
            <!-- Graphic Element / Featured Badge -->
            <div class="relative z-10 flex flex-col items-center bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 shadow-sm flex-shrink-0 text-center w-72">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-orange-500 mb-4 shadow-md shadow-orange-500/20">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h4 class="font-bold text-lg mb-1">100% Produk Lokal</h4>
                <p class="text-xs text-orange-100">Semua produk dikurasi langsung dari pengrajin dan UMKM terverifikasi.</p>
            </div>
        </div>

        <!-- Categories List -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 flex items-center space-x-2">
                <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span>
                <span>Kategori Pilihan</span>
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="bg-white border border-slate-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-md hover:scale-[1.03] transition duration-200 flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-700">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Featured Products -->
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span>
                    <span>Produk Unggulan Terbaru</span>
                </h3>
                <a href="{{ route('products.index') }}" class="text-xs text-orange-600 hover:text-orange-700 font-bold transition flex items-center space-x-1">
                    <span>Lihat Semua Produk</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            @if($featuredProducts->isEmpty())
                <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                    <p class="text-slate-400 text-sm">Belum ada produk terdaftar saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($featuredProducts as $product)
                        <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                            <!-- Image Area -->
                            <div class="aspect-square bg-slate-50 relative overflow-hidden flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12 group-hover:scale-110 transition duration-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                </svg>
                                @if($product->stock <= 0)
                                    <div class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                        Habis
                                    </div>
                                @endif
                            </div>

                            <!-- Detail Area -->
                            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                                <div>
                                    <span class="text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded border border-orange-100 block w-max mb-1.5">
                                        {{ $product->categories->first()->name ?? 'Umum' }}
                                    </span>
                                    <a href="{{ route('products.show', $product->slug) }}" class="block text-slate-700 hover:text-orange-600 font-semibold text-xs mb-1 line-clamp-2 leading-snug group-hover:underline">
                                        {{ $product->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block font-medium">Toko: {{ $product->shop->name }}</span>
                                </div>

                                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block">Harga</span>
                                        <span class="font-extrabold text-slate-800 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-slate-400 block">Stok</span>
                                        <span class="text-[10px] font-bold {{ $product->stock > 5 ? 'text-slate-500' : ($product->stock > 0 ? 'text-amber-500' : 'text-red-500') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                                        <form action="{{ route('cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full py-2 bg-orange-600 hover:bg-orange-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center space-x-1 shadow-sm shadow-orange-100">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                                <span>+ Keranjang</span>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block text-center w-full py-2 border border-slate-200 text-slate-500 hover:bg-slate-50 text-[10px] font-bold rounded-xl transition">
                                        Beli
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
