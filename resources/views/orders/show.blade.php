<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto space-y-6">
        <!-- Back navigation -->
        <div>
            <a href="{{ route('orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
        </div>

        <!-- Main Invoice details -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-6 mb-6 gap-4">
                <div>
                    <span class="text-xs text-slate-400 block">No. Invoice</span>
                    <h1 class="text-2xl font-bold text-slate-800">{{ $order->invoice_number }}</h1>
                    <span class="text-xs text-slate-400">Dibuat pada: {{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block mb-1">Status Pesanan</span>
                    @if($order->status === 'pending_payment')
                        <span class="text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1 rounded-full">Menunggu Pembayaran</span>
                    @elseif($order->status === 'pending_confirmation')
                        <span class="text-xs font-bold bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1 rounded-full">Menunggu Konfirmasi</span>
                    @elseif($order->status === 'processing')
                        <span class="text-xs font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-1 rounded-full">Diproses Penjual</span>
                    @elseif($order->status === 'shipped')
                        <span class="text-xs font-bold bg-purple-50 border border-purple-200 text-purple-700 px-3 py-1 rounded-full">Dalam Pengiriman</span>
                    @elseif($order->status === 'completed')
                        <span class="text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full">Selesai</span>
                    @elseif($order->status === 'cancelled')
                        <span class="text-xs font-bold bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1 rounded-full">Dibatalkan</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-6 mb-6">
                <!-- Shipping details -->
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-3">Informasi Pengiriman</h3>
                    <div class="space-y-1.5 text-sm text-slate-600">
                        <p class="leading-relaxed whitespace-pre-line">{{ $order->shipping_address }}</p>
                        <p><span class="text-slate-400">Kurir:</span> <span class="font-semibold text-slate-700">{{ $order->courier }}</span></p>
                        @if($order->tracking_number)
                            <p><span class="text-slate-400">No. Resi:</span> <span class="font-bold text-indigo-600">{{ $order->tracking_number }}</span></p>
                        @else
                            <p class="text-xs text-slate-400 italic">No. resi belum tersedia</p>
                        @endif
                    </div>
                </div>

                <!-- Payment status details -->
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-3">Informasi Pembayaran</h3>
                    <div class="space-y-1.5 text-sm text-slate-600">
                        @if($order->payment)
                            <p><span class="text-slate-400">Metode:</span> <span class="font-semibold text-slate-700">{{ $order->payment->payment_method }}</span></p>
                            <p><span class="text-slate-400">Jumlah Bayar:</span> <span class="font-semibold text-slate-700">Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</span></p>
                            <p>
                                <span class="text-slate-400">Status Pembayaran:</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $order->payment->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $order->payment->payment_status }}
                                </span>
                            </p>
                            @if($order->payment->paid_at)
                                <p class="text-xs text-slate-400">Dibayar pada: {{ $order->payment->paid_at }}</p>
                            @endif
                        @else
                            <p class="text-amber-600 font-medium">Pembayaran belum diterima.</p>
                            @if($order->status === 'pending_payment')
                                <a href="{{ route('payments.create', $order->id) }}" class="inline-flex mt-2 items-center px-4 py-2 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition shadow-sm">
                                    Unggah Bukti Pembayaran
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4">Item yang Dipesan</h3>
                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-slate-50 border rounded-lg flex items-center justify-center text-slate-300 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                        </svg>
                                    </div>
                                    <div>
                                        <a href="{{ route('products.show', $item->product->slug) }}" class="text-slate-800 text-sm font-semibold hover:text-indigo-600 transition leading-tight block line-clamp-1">{{ $item->product->name }}</a>
                                        <span class="text-xs text-slate-400 block">{{ $item->qty }} pcs x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>

                            <!-- Review section for completed orders -->
                            @if($order->status === 'completed')
                                <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                    @if($item->review)
                                        <div class="text-xs text-slate-500">
                                            <span class="font-bold text-slate-700 block mb-1">Ulasan Anda:</span>
                                            <div class="flex items-center text-amber-500 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $item->review->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="italic">"{{ $item->review->comment }}"</p>
                                        </div>
                                    @else
                                        <!-- Review Form -->
                                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                            <div class="flex items-center space-x-3">
                                                <label for="rating-{{ $item->id }}" class="text-xs font-semibold text-slate-600">Berikan Rating:</label>
                                                <select name="rating" id="rating-{{ $item->id }}" required class="border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-1 px-3 text-xs bg-white">
                                                    <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Baik)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4 - Baik)</option>
                                                    <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                                    <option value="2">⭐⭐ (2 - Kurang)</option>
                                                    <option value="1">⭐ (1 - Buruk)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <textarea name="comment" rows="2" placeholder="Tuliskan pengalaman Anda menggunakan produk ini..." class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg p-2 text-xs placeholder-slate-400"></textarea>
                                            </div>
                                            <button type="submit" class="py-1.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                Kirim Ulasan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t border-slate-100 pt-6 mt-6 flex justify-end">
                <div class="w-80 space-y-2.5 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Total Harga Barang:</span>
                        <span class="font-semibold text-slate-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Ongkos Kirim:</span>
                        <span class="font-semibold text-slate-700">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-slate-100 my-1" />
                    <div class="flex justify-between text-slate-800 font-bold text-base">
                        <span>Grand Total:</span>
                        <span class="text-indigo-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
