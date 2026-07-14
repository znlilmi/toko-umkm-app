<x-app-layout>
    <div class="py-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-8 flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>Keranjang Belanja Anda</span>
        </h1>

        @if($cartItems->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <h3 class="text-xl font-bold text-slate-700 mb-1">Keranjang Masih Kosong</h3>
                <p class="text-slate-400 text-sm mb-6">Jelajahi produk lokal berkualitas tinggi dan masukkan barang impian Anda di sini.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Items list -->
                <div class="lg:col-span-2 space-y-6">
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach($cartItems as $item)
                        @php
                            $itemSubtotal = $item->product->price * $item->qty;
                            $subtotal += $itemSubtotal;
                        @endphp
                        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                            <!-- Left: Product detail info -->
                            <div class="flex items-center space-x-4 w-full sm:w-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-xl border border-slate-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('images/default_product.png') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">Toko: {{ $item->product->shop->name }}</span>
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="block font-semibold text-slate-800 hover:text-indigo-600 transition leading-snug line-clamp-1 mb-1">{{ $item->product->name }}</a>
                                    <span class="text-sm font-extrabold text-slate-700 block">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Right: Form actions -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto border-t sm:border-t-0 pt-4 sm:pt-0">
                                <!-- Qty Update Form -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->product->stock }}" class="w-16 text-center border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-1 px-2 text-xs">
                                    <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-semibold transition border border-indigo-100">
                                        Update
                                    </button>
                                </form>

                                <!-- Subtotal for item -->
                                <div class="text-right min-w-[100px]">
                                    <span class="text-[10px] text-slate-400 block">Subtotal</span>
                                    <span class="text-sm font-bold text-slate-800">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                </div>

                                <!-- Remove button -->
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-lg transition border border-rose-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Summary Box -->
                <div class="space-y-6">
                    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm sticky top-24">
                        <h3 class="font-bold text-slate-800 text-lg mb-4 border-b border-slate-100 pb-3">Ringkasan Belanja</h3>
                        
                        <div class="space-y-3 mb-6 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Total Item</span>
                                <span class="font-semibold text-slate-700">{{ $cartItems->sum('qty') }} unit</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Harga Barang</span>
                                <span class="font-semibold text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <hr class="border-slate-100 my-2" />
                            <div class="flex justify-between text-slate-800 font-bold text-base">
                                <span>Total Belanja</span>
                                <span class="text-indigo-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('orders.create') }}" class="block text-center w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
