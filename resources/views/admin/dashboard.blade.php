<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Administrator</h1>
            <p class="text-sm text-slate-400">Panel pemantauan performa platform dan moderasi toko UMKM secara global.</p>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Omzet Platform</span>
                    <span class="text-lg md:text-xl font-extrabold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Total Pengguna</span>
                    <span class="text-lg md:text-xl font-extrabold text-slate-800">{{ $totalUsers }} User</span>
                </div>
            </div>

            <!-- Total Shops (Active/Pending) -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Toko Aktif</span>
                    <span class="text-lg md:text-xl font-extrabold text-emerald-600">{{ $totalActiveShops }} / {{ $totalShops }}</span>
                </div>
            </div>

            <!-- Pending Shop applications -->
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Verifikasi Toko</span>
                    <span class="text-lg md:text-xl font-extrabold text-amber-600">{{ $pendingShops }} Pengajuan</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent platform orders -->
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b pb-3 border-slate-100">Daftar Transaksi Terbaru</h3>
                @if($recentOrders->isEmpty())
                    <p class="text-sm text-slate-400 py-4 text-center">Belum ada transaksi terjadi.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-slate-500">
                            <thead class="text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2">Invoice</th>
                                    <th class="px-4 py-2">Toko</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $order->invoice_number }}</td>
                                        <td class="px-4 py-3">{{ $order->shop->name }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-slate-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
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

            <!-- Recent users registrations -->
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b pb-3 border-slate-100">User Baru Mendaftar</h3>
                @if($recentUsers->isEmpty())
                    <p class="text-sm text-slate-400 py-4 text-center">Belum ada user mendaftar.</p>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($recentUsers as $user)
                            <div class="py-3 flex justify-between items-center text-xs text-slate-600 first:pt-0 last:pb-0">
                                <div>
                                    <span class="font-semibold text-slate-800 block">{{ $user->name }}</span>
                                    <span class="text-slate-400 block">{{ $user->email }}</span>
                                </div>
                                <span class="text-[9px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded uppercase">{{ $user->role }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
