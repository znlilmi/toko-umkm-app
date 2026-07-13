<x-app-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 border border-slate-800">
            <!-- Decorative light blur -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-violet-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 space-y-2 text-center md:text-left">
                <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-3.5 py-1 rounded-full border border-indigo-500/30 uppercase tracking-widest">
                    Panel Administrator
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Ringkasan Platform</h1>
                <p class="text-indigo-200 text-sm max-w-xl leading-relaxed">Pantau performa omzet keuangan, aktivitas verifikasi toko UMKM, dan metrik pendaftaran pengguna secara global.</p>
            </div>
            
            <div class="relative z-10 flex items-center bg-white/5 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 shadow-sm gap-3 flex-shrink-0">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></div>
                <span class="text-sm font-semibold text-indigo-100">Platform Online & Stabil</span>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.02] transition duration-300 flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Omzet Platform</span>
                    <span class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.403 1.052 3.837 1.052 5.24 0l.879-.659m-7-5.515l.879-.659c1.403-1.052 3.837-1.052 5.24 0l.879.659M8.25 12h7.5" />
                    </svg>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.02] transition duration-300 flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Pengguna</span>
                    <span class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">{{ $totalUsers }} Pengguna</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21m-4.21-1.484A9.125 9.125 0 0110 19.5c.875 0 1.726.124 2.535.358m-6.663-1.484a9.355 9.355 0 01-1.016-1.509m6.663 1.484a9.355 9.355 0 001.016-1.509m-7.679-.007a9.333 9.333 0 01-.225-2.227 4.125 4.125 0 017.532-2.493M10 5.25a3 3 0 003 3H7a3 3 0 003-3z" />
                    </svg>
                </div>
            </div>

            <!-- Total Shops (Active/Pending) -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.02] transition duration-300 flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Toko Aktif</span>
                    <span class="text-xl md:text-2xl font-extrabold text-emerald-600 tracking-tight">{{ $totalActiveShops }} <span class="text-xs text-slate-400 font-normal">dari {{ $totalShops }}</span></span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z" />
                    </svg>
                </div>
            </div>

            <!-- Pending Shop applications -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.02] transition duration-300 flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Verifikasi Toko</span>
                    <span class="text-xl md:text-2xl font-extrabold text-amber-600 tracking-tight">{{ $pendingShops }} Pengajuan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent platform orders -->
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8 space-y-6">
                <h3 class="font-bold text-slate-800 text-lg border-b pb-4 border-slate-100 flex items-center space-x-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    <span>Daftar Transaksi Terbaru</span>
                </h3>
                
                @if($recentOrders->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                        </svg>
                        <p class="text-sm">Belum ada transaksi terjadi di platform.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-xl">No. Invoice</th>
                                    <th class="px-4 py-3">Nama Toko</th>
                                    <th class="px-4 py-3 text-right">Grand Total</th>
                                    <th class="px-4 py-3 text-center rounded-r-xl">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-4 py-3.5 font-semibold text-indigo-600">{{ $order->invoice_number }}</td>
                                        <td class="px-4 py-3.5 text-slate-700 font-medium">{{ $order->shop->name }}</td>
                                        <td class="px-4 py-3.5 text-right font-extrabold text-slate-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-full border {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
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
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8 space-y-6">
                <h3 class="font-bold text-slate-800 text-lg border-b pb-4 border-slate-100 flex items-center space-x-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    <span>User Baru Mendaftar</span>
                </h3>
                
                @if($recentUsers->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952" />
                        </svg>
                        <p class="text-sm">Belum ada user baru mendaftar hari ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto pr-2">
                        @foreach($recentUsers as $user)
                            <div class="py-3 flex justify-between items-center text-xs first:pt-0 last:pb-0">
                                <div class="flex items-center space-x-3">
                                    <!-- Initials Avatar -->
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 font-bold flex items-center justify-center border border-slate-200">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">{{ $user->name }}</span>
                                        <span class="text-slate-400 block text-[10px]">{{ $user->email }}</span>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded border border-slate-200 uppercase {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-600' : ($user->role === 'merchant' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-50 text-slate-500') }}">
                                    {{ $user->role }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
