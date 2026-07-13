<x-app-layout>
    <div class="py-6">
        <!-- Catalog Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-violet-500 rounded-2xl p-8 mb-8 text-white shadow-lg shadow-indigo-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">Jelajahi Produk Kreatif UMKM</h1>
                <p class="text-indigo-100 text-sm">Dukung produk lokal berkualitas tinggi langsung dari produsen UMKM terbaik Indonesia.</p>
            </div>
            <!-- Search bar -->
            <form action="{{ route('products.index') }}" method="GET" class="w-full md:w-96 flex bg-white/10 backdrop-blur-md p-1.5 rounded-xl border border-white/20">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang atau toko..." class="flex-1 bg-transparent border-0 text-white placeholder-indigo-200 text-sm focus:ring-0 px-3">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('min_price'))
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                @endif
                @if(request('max_price'))
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                @endif
                <button type="submit" class="bg-white text-indigo-700 hover:bg-indigo-50 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Cari
                </button>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="w-full lg:w-64 flex-shrink-0 space-y-6 sticky top-24">
                <!-- Kategori Filter -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Kategori</h3>
                    <div class="space-y-1">
                        <a href="{{ route('products.index', request()->only(['q', 'min_price', 'max_price'])) }}" class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Semua Kategori</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', array_merge(request()->only(['q', 'min_price', 'max_price']), ['category' => $category->slug])) }}" class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition {{ request('category') === $category->slug ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>{{ $category->name }}</span>
                            </a>
                            <!-- Child Categories -->
                            @if($category->children->count() > 0)
                                <div class="pl-4 space-y-1 mt-1">
                                    @foreach($category->children as $child)
                                        <a href="{{ route('products.index', array_merge(request()->only(['q', 'min_price', 'max_price']), ['category' => $child->slug])) }}" class="flex items-center justify-between px-3 py-1.5 text-xs rounded-lg transition {{ request('category') === $child->slug ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-slate-50' }}">
                                            <span>{{ $child->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Rentang Harga Filter -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Rentang Harga</h3>
                    <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        
                        <div class="space-y-2">
                            <div>
                                <label for="min_price" class="text-xs text-slate-400 block mb-1">Harga Minimum</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-xs text-slate-400 font-semibold">Rp</span>
                                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full pl-9 pr-3 py-1.5 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs rounded-lg placeholder-slate-300">
                                </div>
                            </div>
                            <div>
                                <label for="max_price" class="text-xs text-slate-400 block mb-1">Harga Maksimum</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-xs text-slate-400 font-semibold">Rp</span>
                                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full pl-9 pr-3 py-1.5 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs rounded-lg placeholder-slate-300">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 py-1.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                Terapkan
                            </button>
                            @if(request('min_price') || request('max_price'))
                                <a href="{{ route('products.index', request()->only(['q', 'category'])) }}" class="py-1.5 px-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg text-center transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1">
                @if($products->isEmpty())
                    <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-700 mb-1">Produk Tidak Ditemukan</h3>
                        <p class="text-slate-400 text-sm mb-4">Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                            Reset Pencarian
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                                <!-- Image Area -->
                                <div class="aspect-square bg-slate-100 relative overflow-hidden flex items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16 group-hover:scale-110 transition duration-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                    @if($product->stock <= 0)
                                        <div class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                            Habis
                                        </div>
                                    @endif
                                </div>

                                <!-- Detail Area -->
                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="mb-2">
                                        <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                                            {{ $product->categories->first()->name ?? 'Umum' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $product->slug) }}" class="block text-slate-700 hover:text-indigo-600 font-semibold text-base mb-1 group-hover:underline line-clamp-2">
                                        {{ $product->name }}
                                    </a>
                                    <!-- Shop info -->
                                    <div class="flex items-center space-x-1.5 mb-3">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z" />
                                        </svg>
                                        <span class="text-xs text-slate-500 font-medium">{{ $product->shop->name }}</span>
                                    </div>

                                    <!-- Price and Stock -->
                                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                        <div>
                                            <span class="text-xs text-slate-400 block">Harga</span>
                                            <span class="font-bold text-slate-800 text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-slate-400 block">Stok</span>
                                            <span class="text-xs font-semibold {{ $product->stock > 5 ? 'text-slate-600' : ($product->stock > 0 ? 'text-amber-600 font-bold' : 'text-rose-600 font-bold') }}">
                                                {{ $product->stock > 0 ? $product->stock . ' pcs' : 'Habis' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- CTA -->
                                    @auth
                                        @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                                            <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-xs font-semibold rounded-xl transition flex items-center justify-center space-x-2 shadow-sm shadow-indigo-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                    <span>Tambah Keranjang</span>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="mt-4 block text-center w-full py-2.5 px-4 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition">
                                            Masuk untuk Membeli
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div>
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
