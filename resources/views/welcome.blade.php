<x-app-layout>
    <div class="space-y-10 py-4">
        <!-- Hero Banner Section (Blibli Slider Mockup) -->
        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-100 bg-white">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <!-- Banner Image -->
                <div class="w-full lg:w-3/5 h-64 lg:h-96 relative overflow-hidden">
                    <img src="{{ asset('images/blibli_promo_banner.png') }}" alt="Blibli Promo Banner" class="w-full h-full object-cover">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/30 to-transparent"></div>
                </div>
                
                <!-- Side Promo Card -->
                <div class="w-full lg:w-2/5 p-8 lg:p-10 flex flex-col justify-center space-y-5 bg-white">
                    <span class="text-[10px] font-black uppercase tracking-wider text-orange-500 bg-orange-50 border border-orange-200 px-3 py-1 rounded-full w-max animate-pulse">
                        Rekomendasi Spesial
                    </span>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-800 leading-tight">
                        Dukung UMKM Kebanggaan Kita!
                    </h2>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Temukan koleksi kerajinan tangan, batik, makanan khas daerah, dan inovasi lokal terbaik dengan standar kualitas tinggi yang dikurasi khusus untuk Anda.
                    </p>
                    <div class="flex items-center gap-4 pt-2">
                        <a href="{{ route('products.index') }}" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-100 transition duration-200 text-center flex-1">
                            Mulai Belanja
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="px-5 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition duration-200 text-center flex-1">
                                Daftar Gratis
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            
            <!-- Dots indicator -->
            <div class="absolute bottom-4 left-6 flex items-center space-x-2">
                <span class="w-6 h-2 bg-indigo-600 rounded-full transition-all duration-300"></span>
                <span class="w-2 h-2 bg-white/60 hover:bg-white rounded-full cursor-pointer transition-all"></span>
                <span class="w-2 h-2 bg-white/60 hover:bg-white rounded-full cursor-pointer transition-all"></span>
            </div>
        </div>

        <!-- Categories List (Blibli Circle style) -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-slate-800 flex items-center space-x-2">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                    <span>Kategori Populer</span>
                </h3>
            </div>
            <div class="flex items-center gap-4 md:gap-8 overflow-x-auto pb-2 scrollbar-none">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex flex-col items-center gap-2 group flex-shrink-0 w-24">
                        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center group-hover:border-indigo-300 group-hover:scale-105 transition duration-200">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600 text-center line-clamp-1 w-full">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Histeria Flash Sale Section -->
        <div class="bg-indigo-600 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-4">
                <span class="text-3xl font-black tracking-tight flex items-center gap-2">
                    <span class="text-orange-500">⚡</span> Histeria Lokal
                </span>
                <div class="flex items-center gap-1.5 text-xs font-bold bg-white/20 px-3 py-1 rounded-full border border-white/20">
                    <span>Berakhir dalam:</span>
                    <span class="bg-orange-500 px-2 py-0.5 rounded text-white font-black animate-pulse">01</span>:
                    <span class="bg-orange-500 px-2 py-0.5 rounded text-white font-black animate-pulse">45</span>:
                    <span class="bg-orange-500 px-2 py-0.5 rounded text-white font-black animate-pulse">20</span>
                </div>
            </div>
            <p class="relative z-10 text-xs text-indigo-100 max-w-md leading-relaxed text-center md:text-left">
                Jangan lewatkan penawaran diskon hingga 70% dan cashback spesial belanja produk-produk lokal bersertifikat.
            </p>
        </div>

        <!-- Featured Products -->
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-800 flex items-center space-x-2">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                    <span>Rekomendasi Untuk Anda</span>
                </h3>
                <a href="{{ route('products.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            @if($featuredProducts->isEmpty())
                <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                    <p class="text-slate-400 text-sm">Belum ada produk terdaftar saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($featuredProducts as $product)
                        <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:border-slate-200 transition duration-300 flex flex-col h-full relative">
                            <!-- Image Area -->
                            <div class="aspect-square relative overflow-hidden flex items-center justify-center bg-slate-50">
                                <img src="{{ asset('images/default_product.png') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($product->stock <= 0)
                                    <div class="absolute inset-0 bg-slate-900/10 backdrop-blur-[1px] flex items-center justify-center">
                                        <div class="bg-red-500 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                                            Habis
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Detail Area -->
                            <div class="p-3.5 flex-1 flex flex-col justify-between gap-2.5">
                                <div class="space-y-1.5">
                                    <!-- Category Tag -->
                                    <span class="text-[9px] text-indigo-600 font-bold bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded w-max block">
                                        {{ $product->categories->first()->name ?? 'Umum' }}
                                    </span>
                                    
                                    <!-- Product Name -->
                                    <a href="{{ route('products.show', $product->slug) }}" class="block text-slate-700 hover:text-indigo-600 font-bold text-xs line-clamp-2 leading-snug group-hover:underline">
                                        {{ $product->name }}
                                    </a>
                                </div>

                                <div class="space-y-1.5">
                                    <!-- Price -->
                                    <div>
                                        <span class="text-[10px] text-slate-400 block leading-none">Harga</span>
                                        <span class="font-black text-indigo-600 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>

                                    <!-- Rating & Shop Details -->
                                    <div class="flex items-center justify-between gap-2 pt-1.5 border-t border-slate-100">
                                        <div class="flex flex-col">
                                            <span class="text-[9px] text-slate-500 font-black truncate max-w-[80px]">{{ $product->shop->name }}</span>
                                            <!-- City Location -->
                                            <span class="text-[8px] text-slate-400 font-bold truncate max-w-[80px]">
                                                📍 {{ config('cities')[$product->shop->city_id] ?? 'Indonesia' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center text-amber-500 text-[9px] font-bold">
                                            <span>⭐</span>
                                            <span class="text-slate-700 ml-0.5">{{ number_format($product->rating ?? 4.5, 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="pt-1">
                                    @auth
                                        @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                                            <form action="{{ route('cart.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-[10px] font-bold rounded-xl transition flex items-center justify-center space-x-1 shadow-md shadow-indigo-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                    <span>Beli</span>
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
