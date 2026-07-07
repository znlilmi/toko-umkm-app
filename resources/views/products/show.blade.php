<x-app-layout>
    <div class="py-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-slate-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('products.index') }}" class="ms-1 hover:text-indigo-600">Produk</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-slate-400 font-medium line-clamp-1">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Product Card -->
        <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm p-6 md:p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                <!-- Left: Product Image Placeholder -->
                <div class="aspect-square bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-300 relative">
                    <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                    </svg>
                    @if($product->stock <= 0)
                        <div class="absolute top-4 left-4 bg-rose-500 text-white text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                            Stok Habis
                        </div>
                    @endif
                </div>

                <!-- Right: Product Info & Buy Box -->
                <div class="flex flex-col">
                    <!-- Categories -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($product->categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 hover:bg-indigo-100 transition">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">{{ $product->name }}</h1>

                    <!-- Rating & Shop details -->
                    <div class="flex flex-wrap items-center gap-4 text-sm mb-6 border-b border-slate-100 pb-4">
                        <!-- Rating -->
                        <div class="flex items-center text-amber-500 font-semibold">
                            <svg class="w-4 h-4 mr-1 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span>{{ $product->rating > 0 ? number_format($product->rating, 1) : 'Belum dinilai' }}</span>
                            <span class="text-slate-400 font-normal ms-1">({{ $product->reviews->count() }} Ulasan)</span>
                        </div>

                        <!-- Shop -->
                        <div class="text-slate-500 flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span class="font-medium text-slate-700">Toko: {{ $product->shop->name }}</span>
                            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-0.5 rounded border">{{ $product->shop->status }}</span>
                        </div>
                    </div>

                    <!-- Price Box -->
                    <div class="mb-6">
                        <span class="text-xs text-slate-400 block mb-1">Harga Terbaik</span>
                        <span class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Produk</h3>
                        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>
                    </div>

                    <!-- Product Specifications / Quick Info -->
                    <div class="grid grid-cols-2 gap-4 mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block">Stok Tersedia</span>
                            <span class="font-semibold text-slate-700 text-sm {{ $product->stock > 0 ? '' : 'text-rose-500' }}">
                                {{ $product->stock > 0 ? $product->stock . ' unit' : 'Stok Habis' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Berat Produk</span>
                            <span class="font-semibold text-slate-700 text-sm">{{ number_format($product->weight, 0) }} gram</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    @auth
                        @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Cart Add Form -->
                                <form action="{{ route('cart.store') }}" method="POST" class="flex-1 flex gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <!-- Qty input -->
                                    <div class="w-24">
                                        <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full text-center border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3 text-sm">
                                    </div>
                                    <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="flex-1 py-3 px-6 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition flex items-center justify-center space-x-2 shadow-lg shadow-indigo-100">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <span>Masukkan Keranjang</span>
                                    </button>
                                </form>

                                <!-- Wishlist Toggle -->
                                <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="py-3 px-4 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl transition flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'text-rose-500 fill-current' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full py-3 px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                            Masuk untuk Melakukan Transaksi Belanja
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm p-6 md:p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Ulasan Pembeli</h3>
            
            @if($product->reviews->isEmpty())
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-7.5 5.25a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <p class="text-sm">Belum ada ulasan untuk produk ini.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($product->reviews as $review)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-slate-700 text-sm">Pembeli Anonim</span>
                                <div class="flex items-center text-amber-500 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="text-slate-400 ms-1">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            <p class="text-slate-600 text-sm">{{ $review->comment ?? 'Tidak ada komentar tertulis.' }}</p>
                            <span class="text-[10px] text-slate-400 block mt-2">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
