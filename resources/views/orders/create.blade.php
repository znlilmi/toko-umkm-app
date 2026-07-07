<x-app-layout>
    <div class="py-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-8 flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Checkout Pesanan</span>
        </h1>

        @if($cartItems->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <p class="text-slate-500 mb-4">Keranjang belanja Anda kosong, tidak ada item untuk checkout.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Shipping & Items info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Shipping address selection -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-slate-800 text-base">Alamat Pengiriman</h3>
                                <a href="{{ route('addresses.create') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span>Tambah Alamat</span>
                                </a>
                            </div>

                            @if($addresses->isEmpty())
                                <div class="p-4 border-2 border-dashed border-slate-200 rounded-xl text-center">
                                    <p class="text-sm text-slate-400 mb-3">Anda belum memiliki alamat terdaftar.</p>
                                    <a href="{{ route('addresses.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition">
                                        Buat Alamat Pertama
                                    </a>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($addresses as $address)
                                        <label class="flex items-start p-4 border rounded-xl hover:bg-slate-50 cursor-pointer transition {{ $address->is_default ? 'border-indigo-200 bg-indigo-50/20' : 'border-slate-100' }}">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="ms-3 space-y-0.5">
                                                <span class="text-sm font-bold text-slate-700 block">
                                                    {{ $address->recipient_name }}
                                                    @if($address->is_default)
                                                        <span class="text-[9px] font-bold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ms-1 border border-indigo-200 uppercase">Utama</span>
                                                    @endif
                                                </span>
                                                <span class="text-xs text-slate-400 block">{{ $address->phone }}</span>
                                                <span class="text-xs text-slate-600 leading-relaxed block">{{ $address->address_line }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('address_id')
                                    <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>

                        <!-- Shipping courier selection -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                            <h3 class="font-bold text-slate-800 text-base mb-4">Metode Pengiriman</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <label class="flex flex-col items-center justify-center p-4 border border-slate-100 rounded-xl hover:bg-slate-50 cursor-pointer transition text-center">
                                    <input type="radio" name="courier" value="JNE" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 mb-2">
                                    <span class="text-sm font-bold text-slate-700">JNE</span>
                                    <span class="text-[10px] text-slate-400">Regular Service</span>
                                </label>
                                <label class="flex flex-col items-center justify-center p-4 border border-slate-100 rounded-xl hover:bg-slate-50 cursor-pointer transition text-center">
                                    <input type="radio" name="courier" value="TIKI" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 mb-2">
                                    <span class="text-sm font-bold text-slate-700">TIKI</span>
                                    <span class="text-[10px] text-slate-400">Regular Service</span>
                                </label>
                                <label class="flex flex-col items-center justify-center p-4 border border-slate-100 rounded-xl hover:bg-slate-50 cursor-pointer transition text-center">
                                    <input type="radio" name="courier" value="POS" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 mb-2">
                                    <span class="text-sm font-bold text-slate-700">POS Indonesia</span>
                                    <span class="text-[10px] text-slate-400">Kilat Khusus</span>
                                </label>
                            </div>
                            @error('courier')
                                <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Cart items list display -->
                        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                            <h3 class="font-bold text-slate-800 text-base mb-4">Rincian Barang</h3>
                            <div class="divide-y divide-slate-100">
                                @php
                                    $subtotal = 0;
                                    $totalWeight = 0;
                                @endphp
                                @foreach($cartItems as $item)
                                    @php
                                        $itemSubtotal = $item->product->price * $item->qty;
                                        $subtotal += $itemSubtotal;
                                        $totalWeight += $item->product->weight * $item->qty;
                                    @endphp
                                    <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                        <div class="flex items-center space-x-3">
                                            <input type="hidden" name="cart_item_ids[]" value="{{ $item->id }}">
                                            <div class="w-12 h-12 bg-slate-50 border rounded-lg flex items-center justify-center text-slate-300 flex-shrink-0">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-slate-800 text-sm font-semibold block leading-tight line-clamp-1">{{ $item->product->name }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $item->qty }} pcs x Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary buy panel -->
                    <div class="space-y-6">
                        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm sticky top-24">
                            <h3 class="font-bold text-slate-800 text-base mb-4 border-b border-slate-100 pb-3">Ringkasan Pembayaran</h3>
                            
                            @php
                                // Mock shipping rate: e.g. Rp 15.000 flat or 15.000 per kg
                                $shippingCost = ceil($totalWeight / 1000) * 15000;
                                if ($shippingCost == 0) $shippingCost = 15000;
                                $grandTotal = $subtotal + $shippingCost;
                            @endphp

                            <div class="space-y-3 mb-6 text-sm">
                                <div class="flex justify-between text-slate-500">
                                    <span>Total Harga Barang</span>
                                    <span class="font-semibold text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Total Berat ({{ number_format($totalWeight, 0) }} g)</span>
                                    <span class="font-semibold text-slate-700">{{ number_format($totalWeight / 1000, 1) }} kg</span>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Ongkos Kirim</span>
                                    <span class="font-semibold text-slate-700">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                                </div>
                                <hr class="border-slate-100 my-2" />
                                <div class="flex justify-between text-slate-800 font-bold text-base">
                                    <span>Total Bayar</span>
                                    <span class="text-indigo-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" {{ $addresses->isEmpty() ? 'disabled' : '' }} class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-indigo-100">
                                Buat Pesanan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
