<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800 mb-8 flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375c.9 0 1.625-.724 1.625-1.625v-1.125c0-.9-.724-1.625-1.625-1.625H9M21 12c0-1.268-.63-2.39-1.593-3.068a3.745 3.745 0 00-2.523-2.523 3.745 3.745 0 00-3.068-1.593 3.746 3.746 0 00-3.068 1.593 3.745 3.745 0 00-2.523 2.523 3.745 3.745 0 00-1.593 3.068c0 1.268.63 2.39 1.593 3.068a3.745 3.745 0 002.523 2.523 3.746 3.746 0 003.068 1.593 3.746 3.746 0 003.068-1.593 3.745 3.745 0 002.523-2.523 3.745 3.745 0 001.593-3.068z" />
            </svg>
            <span>Pesanan Saya</span>
        </h1>

        @if($orders->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-slate-700 mb-1">Belum Ada Transaksi</h3>
                <p class="text-slate-400 text-sm mb-4">Anda belum pernah melakukan pemesanan barang.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <div class="flex items-center space-x-3 text-xs text-slate-500">
                                <span class="font-bold text-slate-700 text-sm">{{ $order->invoice_number }}</span>
                                <span>|</span>
                                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                <span>|</span>
                                <span class="font-medium text-slate-700">Toko: {{ $order->shop->name }}</span>
                            </div>
                            
                            <!-- Status Badges -->
                            <div>
                                @if($order->status === 'pending_payment')
                                    <span class="text-[10px] uppercase font-bold bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-0.5 rounded-full">Menunggu Pembayaran</span>
                                @elseif($order->status === 'pending_confirmation')
                                    <span class="text-[10px] uppercase font-bold bg-blue-50 border border-blue-200 text-blue-700 px-2.5 py-0.5 rounded-full">Menunggu Konfirmasi</span>
                                @elseif($order->status === 'processing')
                                    <span class="text-[10px] uppercase font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-0.5 rounded-full">Diproses Penjual</span>
                                @elseif($order->status === 'shipped')
                                    <span class="text-[10px] uppercase font-bold bg-purple-50 border border-purple-200 text-purple-700 px-2.5 py-0.5 rounded-full">Dalam Pengiriman</span>
                                @elseif($order->status === 'completed')
                                    <span class="text-[10px] uppercase font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-0.5 rounded-full">Selesai</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="text-[10px] uppercase font-bold bg-rose-50 border border-rose-200 text-rose-700 px-2.5 py-0.5 rounded-full">Dibatalkan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- Product list preview -->
                            <div class="space-y-4 mb-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-slate-50 border rounded-lg flex items-center justify-center text-slate-300 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-slate-800 text-sm font-semibold block truncate leading-tight">{{ $item->product->name }}</span>
                                            <span class="text-xs text-slate-400 block">{{ $item->qty }} pcs x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals & Actions -->
                            <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <span class="text-xs text-slate-400 block">Total Tagihan</span>
                                    <span class="text-base font-extrabold text-slate-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 rounded-lg transition border border-slate-200 text-center">
                                        Detail Pesanan
                                    </a>

                                    @if($order->status === 'pending_payment')
                                        <a href="{{ route('payments.create', $order->id) }}" class="px-4 py-2 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition text-center shadow-sm shadow-amber-100">
                                            Bayar Sekarang
                                        </a>
                                    @endif

                                    @if($order->status === 'shipped')
                                        <form action="{{ route('orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda sudah menerima pesanan dengan baik?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition text-center shadow-sm">
                                                Konfirmasi Terima Barang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
