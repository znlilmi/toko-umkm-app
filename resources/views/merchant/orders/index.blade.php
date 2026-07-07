<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pesanan Masuk</h1>
            <p class="text-sm text-slate-400">Proses pemesanan barang masuk dari pelanggan toko Anda.</p>
        </div>

        <!-- Filter Tab Links -->
        <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-px">
            @php
                $statuses = [
                    '' => 'Semua',
                    'pending_payment' => 'Belum Bayar',
                    'pending_confirmation' => 'Perlu Konfirmasi',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Batal'
                ];
            @endphp
            @foreach($statuses as $key => $label)
                <a href="{{ route('merchant.orders.index', $key ? ['status' => $key] : []) }}" class="px-4 py-2 text-xs font-semibold rounded-t-xl transition border-b-2 {{ request('status') == $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Orders Table List -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($orders->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-700 mb-1">Tidak Ada Pesanan</h3>
                    <p class="text-slate-400 text-sm">Pesanan dengan kriteria status ini belum tersedia.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">No. Invoice</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Kurir</th>
                                <th class="px-6 py-4 text-right">Total Tagihan</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm mb-0.5">{{ $order->invoice_number }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-slate-700 font-medium text-xs">{{ $order->customer->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-slate-500 font-semibold text-xs">{{ $order->courier }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-extrabold text-slate-800">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($order->status === 'pending_payment')
                                            <span class="text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded">Belum Bayar</span>
                                        @elseif($order->status === 'pending_confirmation')
                                            <span class="text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded">Konfirmasi</span>
                                        @elseif($order->status === 'processing')
                                            <span class="text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 rounded">Diproses</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="text-[9px] font-bold bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded">Dikirim</span>
                                        @elseif($order->status === 'completed')
                                            <span class="text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded">Selesai</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 rounded">Batal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('merchant.orders.show', $order->id) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                                            Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
