<x-app-layout>
    <div class="space-y-6">
        <!-- Dashboard Welcome & Shop Status Header -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-sm text-slate-400">Selamat datang kembali di panel manajemen toko Anda.</p>
            </div>
            <!-- Shop info card inside dashboard -->
            <div class="flex items-center space-x-3 bg-slate-50 p-3 rounded-2xl border">
                @if($shop->logo)
                    <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover border">
                @else
                    <div class="w-10 h-10 rounded-xl bg-slate-200 border flex items-center justify-center text-slate-400 font-bold">
                        {{ substr($shop->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-slate-700 text-sm">{{ $shop->name }}</h4>
                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 rounded-full {{ $shop->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase">{{ $shop->status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Total Pendapatan</span>
                    <span class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-600">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.22.08a2.25 2.25 0 002.56-1.578l.22-.722a2.25 2.25 0 00-2.56-1.578l-.22-.722a2.25 2.25 0 002.56-1.578l.22.08M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Total Transaksi</span>
                    <span class="text-2xl font-extrabold text-slate-800">{{ $totalOrders }} Pesanan</span>
                </div>
                <div class="p-3 bg-indigo-50 rounded-2xl border border-indigo-100 text-indigo-600">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <!-- Shop Balance -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Saldo Penjual</span>
                    <span class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($shop->balance, 0, ',', '.') }}</span>
                </div>
                <div class="p-3 bg-amber-50 rounded-2xl border border-amber-100 text-amber-600">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0c0 1.243-1.007 2.25-2.25 2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v6z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel: Recent incoming orders list -->
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3 border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Pesanan Masuk Terbaru</h3>
                    <a href="{{ route('merchant.orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Lihat Semua</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-sm">Belum ada pesanan masuk.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-lg">
                                <tr>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Pembeli</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-4 py-3.5 font-semibold text-slate-700">
                                            <a href="{{ route('merchant.orders.show', $order->id) }}" class="hover:text-indigo-600 hover:underline">
                                                {{ $order->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3.5">{{ $order->customer->name }}</td>
                                        <td class="px-4 py-3.5 font-bold text-slate-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Right Panel: Low stock product alerts -->
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3 border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Alert Stok Rendah</h3>
                    <a href="{{ route('merchant.inventory.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Kelola Stok</a>
                </div>

                @if($lowStockProducts->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-sm">Semua stok produk aman.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-rose-50/50 border border-rose-100 rounded-xl">
                                <div class="min-w-0">
                                    <span class="text-slate-800 font-semibold text-xs truncate block leading-tight">{{ $product->name }}</span>
                                    <span class="text-[10px] text-slate-400 block">Sisa stok: {{ $product->stock }} unit</span>
                                </div>
                                <a href="{{ route('merchant.inventory.show', $product->id) }}" class="px-3 py-1 bg-white hover:bg-rose-50 border border-rose-200 text-rose-600 rounded-lg text-[10px] font-bold transition">
                                    Tambah
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
