<x-app-layout>
    {{-- KPI auto-refresh every 5 minutes --}}
    <div class="space-y-6" id="merchant-dashboard">

        {{-- ─────────────────────────────────────────────────────
             HEADER: Welcome + Shop Status + Refresh Indicator
        ──────────────────────────────────────────────────────── --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-violet-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-1">
                <span class="text-xs font-bold bg-white/10 text-indigo-200 px-3 py-1 rounded-full border border-white/10 uppercase tracking-widest">Dashboard Merchant</span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-2">Halo, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-indigo-200 text-sm">Selamat datang kembali di panel manajemen <strong>{{ $shop->name }}</strong>.</p>
            </div>

            <div class="relative z-10 flex items-center gap-3 flex-wrap">
                {{-- Shop Status Badge --}}
                <div class="flex items-center bg-white/10 backdrop-blur-sm px-4 py-2 rounded-2xl border border-white/10 gap-2">
                    @if($shop->logo)
                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover border border-white/20">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($shop->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <div class="text-xs font-bold text-white leading-tight">{{ $shop->name }}</div>
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full {{ $shop->is_active ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                            <span class="text-[10px] text-indigo-200 uppercase font-semibold">{{ $shop->status }}</span>
                        </div>
                    </div>
                </div>

                {{-- Auto-refresh Indicator --}}
                <div class="flex items-center bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-2xl border border-white/10 gap-2">
                    <svg id="refresh-spinner" class="w-3.5 h-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-[10px] text-indigo-200 font-semibold">Refresh dalam <span id="countdown" class="text-emerald-300 font-bold">5:00</span></span>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             KPI CARDS – 4 Metric Cards
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total Pendapatan --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Total Pendapatan</span>
                        <span id="kpi-revenue" class="text-xl font-extrabold text-slate-800 leading-tight block">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400">dari {{ $successfulOrders }} transaksi sukses</span>
                    </div>
                    <div class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.403 1.052 3.837 1.052 5.24 0l.879-.659m-7-5.515l.879-.659c1.403-1.052 3.837-1.052 5.24 0l.879.659" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-50">
                    <span class="text-[10px] text-emerald-600 font-semibold">✓ Transaksi Selesai</span>
                </div>
            </div>

            {{-- Transaksi Sukses --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Transaksi Sukses</span>
                        <span id="kpi-orders" class="text-xl font-extrabold text-slate-800 leading-tight block">
                            {{ $successfulOrders }} <span class="text-sm text-slate-400 font-normal">pesanan</span>
                        </span>
                        <span class="text-[10px] text-slate-400">dari total {{ $totalOrders }} pesanan</span>
                    </div>
                    <div class="p-2.5 bg-indigo-50 rounded-xl border border-indigo-100 text-indigo-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-50 flex gap-3">
                    <span class="text-[10px] text-amber-600 font-semibold">⏳ <span id="kpi-pending">{{ $pendingOrders }}</span> pending</span>
                    <span class="text-[10px] text-blue-600 font-semibold">🚚 <span id="kpi-processing">{{ $processingOrders }}</span> proses</span>
                </div>
            </div>

            {{-- Average Order Value --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Rata-rata Nilai Order</span>
                        <span id="kpi-aov" class="text-xl font-extrabold text-slate-800 leading-tight block">
                            Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400">Average Order Value (AOV)</span>
                    </div>
                    <div class="p-2.5 bg-violet-50 rounded-xl border border-violet-100 text-violet-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-50">
                    <span class="text-[10px] text-violet-600 font-semibold">📊 Per transaksi selesai</span>
                </div>
            </div>

            {{-- Saldo Penjual --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Saldo Siap Tarik</span>
                        <span id="kpi-balance" class="text-xl font-extrabold text-slate-800 leading-tight block">
                            Rp {{ number_format($shop->balance, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400">Saldo tersedia di toko</span>
                    </div>
                    <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-100 text-amber-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0c0 1.243-1.007 2.25-2.25 2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 013 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-50">
                    <span class="text-[10px] text-amber-600 font-semibold">💰 Saldo withdrawal</span>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             CHARTS ROW
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Line Chart: Sales Trend (30 days) --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Tren Penjualan (30 Hari Terakhir)</h3>
                        <p class="text-xs text-slate-400 mt-0.5">GMV & frekuensi transaksi harian</p>
                    </div>
                    <div class="flex items-center gap-3 text-[10px] text-slate-400 font-semibold">
                        <span class="flex items-center gap-1"><span class="w-3 h-1 bg-indigo-500 rounded inline-block"></span> GMV</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-1 bg-emerald-400 rounded inline-block"></span> Transaksi</span>
                    </div>
                </div>
                <div class="relative" style="height: 260px;">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            {{-- Doughnut Chart: Category Distribution --}}
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <div class="mb-5">
                    <h3 class="font-bold text-slate-800 text-base">Kategori Produk Terjual</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Distribusi qty per kategori (selesai)</p>
                </div>
                <div class="relative flex items-center justify-center" style="height: 220px;">
                    <canvas id="categoryChart"></canvas>
                </div>
                {{-- Dynamic legend --}}
                <div id="categoryLegend" class="mt-4 space-y-1.5"></div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             BOTTOM ROW: Recent Orders + Low Stock
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Recent Orders Table --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3 border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Pesanan Masuk Terbaru</h3>
                    <a href="{{ route('merchant.orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Lihat Semua →</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                        </svg>
                        <p class="text-sm">Belum ada pesanan masuk.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-xl">Invoice</th>
                                    <th class="px-4 py-3">Pembeli</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                    <th class="px-4 py-3 text-center rounded-r-xl">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-4 py-3.5 font-semibold text-slate-700">
                                            <a href="{{ route('merchant.orders.show', $order->id) }}" class="hover:text-indigo-600 hover:underline text-xs">
                                                {{ $order->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs">{{ $order->customer->name }}</td>
                                        <td class="px-4 py-3.5 font-bold text-slate-800 text-right text-xs">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            @php
                                                $statusColors = [
                                                    'completed'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'processing'        => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'shipped'           => 'bg-violet-50 text-violet-700 border-violet-200',
                                                    'pending_payment'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'verifying_payment' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                    'cancelled'         => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                                $colorClass = $statusColors[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                            @endphp
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full border {{ $colorClass }}">
                                                {{ str_replace('_', ' ', $order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Low Stock Alerts --}}
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3 border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Alert Stok Rendah</h3>
                    <a href="{{ route('merchant.inventory.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Kelola →</a>
                </div>

                @if($lowStockProducts->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-emerald-200 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Semua stok produk aman.</p>
                    </div>
                @else
                    <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-rose-50/60 border border-rose-100 rounded-xl">
                                <div class="min-w-0">
                                    <span class="text-slate-800 font-semibold text-xs truncate block leading-tight">{{ $product->name }}</span>
                                    <span class="text-[10px] text-rose-500 block font-medium">Sisa: {{ $product->stock }} unit</span>
                                </div>
                                <a href="{{ route('merchant.inventory.show', $product->id) }}" class="px-2.5 py-1 bg-white hover:bg-rose-50 border border-rose-200 text-rose-600 rounded-lg text-[10px] font-bold transition flex-shrink-0">
                                    Restock
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────────────────
         CHART.JS + AUTO-REFRESH SCRIPT
    ──────────────────────────────────────────────────────────────────── --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        'use strict';

        // ── PHP → JS Data ──────────────────────────────────────────────
        const salesData       = @json($salesTrendData);
        const categoryData    = @json($categoryDistributionData);
        const kpiRefreshUrl   = "{{ route('merchant.dashboard.kpi-data') }}";
        const REFRESH_SECONDS = 300; // 5 minutes

        // ── Palette ────────────────────────────────────────────────────
        const palette = [
            '#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981',
            '#3b82f6','#ef4444','#14b8a6','#f97316','#a855f7',
        ];

        // ── Formatters ─────────────────────────────────────────────────
        const fmtRp = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n));

        // ══════════════════════════════════════════════════════════════
        // 1. LINE CHART – Sales Trend
        // ══════════════════════════════════════════════════════════════
        const salesCtx = document.getElementById('salesTrendChart').getContext('2d');

        // Gradient fill
        const gradientGmv = salesCtx.createLinearGradient(0, 0, 0, 260);
        gradientGmv.addColorStop(0, 'rgba(99,102,241,0.18)');
        gradientGmv.addColorStop(1, 'rgba(99,102,241,0.01)');

        const gradientCount = salesCtx.createLinearGradient(0, 0, 0, 260);
        gradientCount.addColorStop(0, 'rgba(52,211,153,0.15)');
        gradientCount.addColorStop(1, 'rgba(52,211,153,0.01)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.labels,
                datasets: [
                    {
                        label: 'GMV (Rp)',
                        data: salesData.gmvArr,
                        borderColor: '#6366f1',
                        backgroundColor: gradientGmv,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#6366f1',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'yGmv',
                    },
                    {
                        label: 'Jumlah Transaksi',
                        data: salesData.countArr,
                        borderColor: '#10b981',
                        backgroundColor: gradientCount,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'yCount',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.datasetIndex === 0
                                ? ' ' + fmtRp(ctx.parsed.y)
                                : ' ' + ctx.parsed.y + ' transaksi',
                        },
                        backgroundColor: 'rgba(15,23,42,0.85)',
                        titleColor: '#94a3b8',
                        bodyColor: '#f1f5f9',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { size: 11 },
                        bodyFont: { size: 12, weight: 'bold' },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            maxTicksLimit: 10,
                            maxRotation: 0,
                        },
                    },
                    yGmv: {
                        type: 'linear',
                        position: 'left',
                        grid: { color: 'rgba(148,163,184,0.1)' },
                        ticks: {
                            color: '#6366f1',
                            font: { size: 10 },
                            callback: (v) => 'Rp ' + Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v),
                        },
                    },
                    yCount: {
                        type: 'linear',
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            color: '#10b981',
                            font: { size: 10 },
                            stepSize: 1,
                        },
                    },
                },
            },
        });

        // ══════════════════════════════════════════════════════════════
        // 2. DOUGHNUT CHART – Category Distribution
        // ══════════════════════════════════════════════════════════════
        const catCtx = document.getElementById('categoryChart').getContext('2d');

        const hasCatData = categoryData.labels && categoryData.labels.length > 0;
        const catLabels = hasCatData ? categoryData.labels : ['Belum ada data'];
        const catValues = hasCatData ? categoryData.values : [1];
        const catColors = hasCatData
            ? catLabels.map((_, i) => palette[i % palette.length])
            : ['#e2e8f0'];

        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: catColors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: hasCatData,
                        callbacks: {
                            label: (ctx) => ' ' + ctx.parsed + ' unit terjual',
                        },
                        backgroundColor: 'rgba(15,23,42,0.85)',
                        bodyColor: '#f1f5f9',
                        padding: 10,
                        cornerRadius: 8,
                    },
                },
            },
        });

        // Render custom legend for category chart
        const legendEl = document.getElementById('categoryLegend');
        if (hasCatData) {
            const total = catValues.reduce((a, b) => a + b, 0);
            catLabels.forEach((label, i) => {
                const pct = total > 0 ? Math.round(catValues[i] / total * 100) : 0;
                legendEl.innerHTML += `
                    <div class="flex items-center justify-between text-[10px]">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:${catColors[i]}"></span>
                            <span class="text-slate-600 truncate">${label}</span>
                        </div>
                        <span class="font-bold text-slate-700 flex-shrink-0 ml-2">${pct}%</span>
                    </div>`;
            });
        } else {
            legendEl.innerHTML = '<p class="text-center text-[10px] text-slate-400">Belum ada penjualan</p>';
        }

        // ══════════════════════════════════════════════════════════════
        // 3. AUTO-REFRESH KPI (Fetch API, every 5 minutes)
        // ══════════════════════════════════════════════════════════════
        const countdownEl = document.getElementById('countdown');
        const spinnerEl   = document.getElementById('refresh-spinner');
        let secondsLeft   = REFRESH_SECONDS;

        function formatTime(s) {
            const m = Math.floor(s / 60).toString().padStart(1, '0');
            const sec = (s % 60).toString().padStart(2, '0');
            return `${m}:${sec}`;
        }

        async function refreshKpi() {
            spinnerEl.classList.add('animate-spin');
            try {
                const res  = await fetch(kpiRefreshUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                });
                if (!res.ok) return;
                const data = await res.json();

                // Update DOM
                document.getElementById('kpi-revenue').textContent =
                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_revenue);
                document.getElementById('kpi-orders').innerHTML =
                    data.successful_orders + ' <span class="text-sm text-slate-400 font-normal">pesanan</span>';
                document.getElementById('kpi-aov').textContent =
                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.average_order_value);
                document.getElementById('kpi-balance').textContent =
                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.shop_balance);
                document.getElementById('kpi-pending').textContent    = data.pending_orders;
                document.getElementById('kpi-processing').textContent = data.processing_orders;

            } catch (e) {
                console.warn('KPI refresh failed', e);
            } finally {
                spinnerEl.classList.remove('animate-spin');
                secondsLeft = REFRESH_SECONDS;
            }
        }

        // Countdown timer (runs every second)
        setInterval(() => {
            secondsLeft--;
            if (countdownEl) countdownEl.textContent = formatTime(secondsLeft);
            if (secondsLeft <= 0) {
                secondsLeft = REFRESH_SECONDS;
                refreshKpi();
            }
        }, 1000);
    })();
    </script>
    @endpush
</x-app-layout>
