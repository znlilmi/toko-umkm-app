<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto space-y-6">
        <div>
            <a href="{{ route('merchant.orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-6 mb-6 gap-4">
                <div class="flex-1">
                    <span class="text-xs text-slate-400 block">No. Invoice</span>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-bold text-slate-800">{{ $order->invoice_number }}</h1>
                        <a href="{{ route('orders.invoice', $order->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Cetak Invoice (PDF)</span>
                        </a>
                    </div>
                    <span class="text-xs text-slate-400">Dibuat pada: {{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block mb-1">Status Pesanan</span>
                    @if($order->status === 'pending_payment')
                        <span class="text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1 rounded-full">Belum Bayar</span>
                    @elseif($order->status === 'pending_confirmation')
                        <span class="text-xs font-bold bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1 rounded-full">Konfirmasi</span>
                    @elseif($order->status === 'processing')
                        <span class="text-xs font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-1 rounded-full">Diproses</span>
                    @elseif($order->status === 'shipped')
                        <span class="text-xs font-bold bg-purple-50 border border-purple-200 text-purple-700 px-3 py-1 rounded-full">Dikirim</span>
                    @elseif($order->status === 'completed')
                        <span class="text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full">Selesai</span>
                    @elseif($order->status === 'cancelled')
                        <span class="text-xs font-bold bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1 rounded-full">Batal</span>
                    @endif
                </div>
            </div>

            <!-- Customer & Shipping -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-6 mb-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-3">Penerima & Alamat</h3>
                    <div class="space-y-1.5 text-sm text-slate-600">
                        <p><span class="text-slate-400">Nama Pelanggan:</span> <span class="font-semibold text-slate-700">{{ $order->customer->name }}</span></p>
                        <p class="leading-relaxed whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-3">Pengiriman & Resi</h3>
                    <div class="space-y-1.5 text-sm text-slate-600">
                        <p><span class="text-slate-400">Kurir:</span> <span class="font-semibold text-slate-700">{{ $order->courier }}</span></p>
                        @if($order->tracking_number)
                            <p><span class="text-slate-400">No. Resi:</span> <span class="font-bold text-indigo-600">{{ $order->tracking_number }}</span></p>
                        @else
                            <p class="text-xs text-slate-400 italic">No. resi belum diunggah</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment details -->
            @if($order->payment)
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-6">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4">Bukti Pembayaran Pelanggan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="space-y-2 text-sm text-slate-600">
                            <p><span class="text-slate-400">Metode:</span> <span class="font-semibold text-slate-700">{{ $order->payment->payment_method }}</span></p>
                            <p><span class="text-slate-400">Jumlah Bayar:</span> <span class="font-semibold text-slate-700">Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</span></p>
                            <p><span class="text-slate-400">Status Bayar:</span> <span class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded">{{ $order->payment->payment_status }}</span></p>
                        </div>
                        @if($order->payment->proof_of_payment)
                            <div>
                                <span class="text-xs text-slate-400 block mb-1">Foto Bukti Transfer:</span>
                                <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank" class="block w-32 aspect-[3/4] bg-white border rounded-xl overflow-hidden shadow-sm hover:opacity-85 transition">
                                    <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" alt="Bukti Transfer" class="w-full h-full object-cover">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4">Rincian Barang Dipesan</h3>
                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-sm">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-slate-50 border rounded-lg flex items-center justify-center text-slate-300 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-slate-800 font-semibold block leading-tight">{{ $item->product->name }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $item->qty }} unit x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <span class="font-bold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Price Summary -->
            <div class="border-t border-slate-100 pt-6 flex justify-end">
                <div class="w-80 space-y-2.5 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal Barang:</span>
                        <span class="font-semibold text-slate-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Biaya Kirim:</span>
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

        <!-- Management Actions Box for Merchant -->
        @if(in_array($order->status, ['pending_confirmation', 'processing']))
            <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-6">Manajemen Pesanan Masuk</h3>
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    
                    @if($order->status === 'pending_confirmation')
                        <!-- Accept Order -->
                        <form action="{{ route('merchant.orders.accept', $order->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                                Terima & Proses Pesanan
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing')
                        <!-- Ship Order Form -->
                        <form action="{{ route('merchant.orders.ship', $order->id) }}" method="POST" class="w-full sm:flex-1 max-w-lg flex flex-col sm:flex-row gap-3">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="tracking_number" placeholder="Masukkan Nomor Resi Pengiriman..." required class="flex-1 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm">
                            <button type="submit" class="py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                                Kirim Barang
                            </button>
                        </form>
                    @endif

                    <!-- Cancel Order -->
                    <form action="{{ route('merchant.orders.cancel', $order->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan otomatis dipulihkan kembali.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-3 px-6 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-semibold rounded-xl transition text-sm">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
