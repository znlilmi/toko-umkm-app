<x-app-layout>
    <div class="space-y-8">

        {{-- ─────────────────────────────────────────────────────
             HEADER
        ──────────────────────────────────────────────────────── --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 border border-slate-800">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-2 text-center md:text-left">
                <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-3.5 py-1 rounded-full border border-indigo-500/30 uppercase tracking-widest">Panel Administrator</span>
                <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Ringkasan Platform</h1>
                <p class="text-indigo-200 text-sm max-w-xl leading-relaxed">Pantau performa omzet keuangan, aktivitas verifikasi toko UMKM, dan metrik pendaftaran pengguna secara global.</p>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-3 flex-shrink-0">
                {{-- Live indicator + refresh --}}
                <div class="flex items-center bg-white/5 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs font-semibold text-indigo-100">Platform Online</span>
                    <span class="text-[10px] text-indigo-300 ml-1">• Refresh <span id="admin-countdown" class="text-emerald-300 font-bold">5:00</span></span>
                </div>
                <a href="{{ route('admin.dashboard.refresh') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-slate-900 bg-white hover:bg-slate-100 rounded-2xl shadow-sm transition gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Refresh Data
                </a>
                <a href="{{ route('admin.reports.commission-pdf') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-slate-900 bg-white hover:bg-slate-100 rounded-2xl shadow-sm transition gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Cetak Laporan Komisi (PDF)
                </a>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             KPI CARDS – 6 Metrics
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

            {{-- Omzet Platform --}}
            <div class="col-span-2 sm:col-span-1 lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Omzet Platform (GMV)</span>
                <span id="admin-kpi-revenue" class="text-2xl font-extrabold text-slate-800 tracking-tight block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">Semua transaksi selesai</span>
            </div>

            {{-- Komisi Diterima --}}
            <div class="col-span-2 sm:col-span-1 lg:col-span-2 bg-gradient-to-br from-indigo-50 to-violet-50 border border-indigo-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider block mb-1">Komisi Platform (5%)</span>
                <span id="admin-kpi-commission" class="text-2xl font-extrabold text-indigo-700 tracking-tight block">Rp {{ number_format($totalCommission, 0, ',', '.') }}</span>
                <span class="text-[10px] text-indigo-400 font-semibold mt-1 block">Pendapatan TokoKita</span>
            </div>

            {{-- Total Orders --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Total Pesanan</span>
                <span id="admin-kpi-orders" class="text-2xl font-extrabold text-slate-800 tracking-tight block">{{ $totalOrders }}</span>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Semua status</span>
            </div>

            {{-- Total Users --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Total Pengguna</span>
                <span id="admin-kpi-users" class="text-2xl font-extrabold text-slate-800 tracking-tight block">{{ $totalUsers }}</span>
                <span class="text-[10px] text-blue-500 font-semibold mt-1 block">Terdaftar</span>
            </div>

            {{-- Active Shops --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Toko Aktif</span>
                <span id="admin-kpi-active-shops" class="text-2xl font-extrabold text-emerald-600 tracking-tight block">{{ $totalActiveShops }}</span>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">dari {{ $totalShops }} total</span>
            </div>

            {{-- Pending Shops --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-300">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Verifikasi Toko</span>
                <span id="admin-kpi-pending" class="text-2xl font-extrabold text-amber-600 tracking-tight block">{{ $pendingShops }}</span>
                <span class="text-[10px] text-amber-500 font-semibold mt-1 block">Pengajuan pending</span>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             CHARTS – ROW 1: Platform Trend (Line Chart)
        ──────────────────────────────────────────────────────── --}}
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded-full inline-block"></span>
                        Tren GMV Platform (30 Hari Terakhir)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 ml-4">Total nilai transaksi selesai per hari di seluruh toko</p>
                </div>
                <div class="flex items-center gap-4 text-[10px] text-slate-500 font-semibold ml-4">
                    <span class="flex items-center gap-1.5"><span class="w-4 h-1 bg-indigo-500 rounded"></span> GMV</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-1 bg-emerald-400 rounded"></span> Transaksi</span>
                </div>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="platformTrendChart"></canvas>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             CHARTS – ROW 2: Merchant Performance + Order Status
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Horizontal Bar Chart: Top 10 Merchants --}}
            <div class="lg:col-span-3 bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-violet-500 rounded-full inline-block"></span>
                        Top 10 Merchant – Performa GMV
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 ml-4">Toko dengan nilai penjualan tertinggi (transaksi selesai)</p>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="merchantPerformanceChart"></canvas>
                </div>
            </div>

            {{-- Doughnut Chart: Order Status Distribution --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full inline-block"></span>
                        Distribusi Status Pesanan
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 ml-4">Komposisi semua pesanan berdasarkan status</p>
                </div>
                <div class="relative flex items-center justify-center" style="height: 220px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div id="statusLegend" class="mt-5 space-y-1.5"></div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────────
             BOTTOM: Recent Orders + Recent Users
        ──────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Recent platform orders --}}
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
                        <p class="text-sm">Belum ada transaksi di platform.</p>
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
                                            @php
                                                $sc = [
                                                    'completed'         => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'processing'        => 'bg-blue-50 text-blue-700 border-blue-100',
                                                    'shipped'           => 'bg-violet-50 text-violet-700 border-violet-100',
                                                    'pending_payment'   => 'bg-amber-50 text-amber-700 border-amber-100',
                                                    'verifying_payment' => 'bg-orange-50 text-orange-700 border-orange-100',
                                                    'cancelled'         => 'bg-rose-50 text-rose-700 border-rose-100',
                                                ];
                                                $cls = $sc[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                            @endphp
                                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-full border {{ $cls }}">
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

            {{-- Recent User Registrations --}}
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 md:p-8 space-y-6">
                <h3 class="font-bold text-slate-800 text-lg border-b pb-4 border-slate-100 flex items-center space-x-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    <span>User Baru Mendaftar</span>
                </h3>

                @if($recentUsers->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <p class="text-sm">Belum ada user baru mendaftar hari ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto pr-2">
                        @foreach($recentUsers as $user)
                            <div class="py-3 flex justify-between items-center text-xs first:pt-0 last:pb-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 font-bold flex items-center justify-center border border-slate-200 text-sm">
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

    {{-- ─────────────────────────────────────────────────────────────────
         CHART.JS + AUTO-REFRESH SCRIPT
    ──────────────────────────────────────────────────────────────────── --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        'use strict';

        // ── PHP → JS Data ──────────────────────────────────────────────
        const platformTrend  = @json($platformTrendData);
        const merchantPerf   = @json($merchantPerformanceData);
        const orderStatus    = @json($orderStatusData);
        const kpiRefreshUrl  = "{{ route('admin.dashboard.kpi-data') }}";
        const REFRESH_SECONDS = 300;

        // ── Palette ────────────────────────────────────────────────────
        const palette = [
            '#6366f1','#8b5cf6','#f59e0b','#10b981','#3b82f6',
            '#ef4444','#ec4899','#14b8a6','#f97316','#a855f7',
        ];

        const statusPalette = {
            'Menunggu Pembayaran' : '#f59e0b',
            'Verifikasi Pembayaran': '#f97316',
            'Diproses'            : '#3b82f6',
            'Dikirim'             : '#8b5cf6',
            'Selesai'             : '#10b981',
            'Dibatalkan'          : '#ef4444',
        };

        const fmtRp = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n));

        // ══════════════════════════════════════════════════════════════
        // 1. LINE CHART – Platform GMV Trend
        // ══════════════════════════════════════════════════════════════
        const trendCtx = document.getElementById('platformTrendChart').getContext('2d');
        const gGmv = trendCtx.createLinearGradient(0, 0, 0, 280);
        gGmv.addColorStop(0, 'rgba(99,102,241,0.18)');
        gGmv.addColorStop(1, 'rgba(99,102,241,0.01)');
        const gCnt = trendCtx.createLinearGradient(0, 0, 0, 280);
        gCnt.addColorStop(0, 'rgba(16,185,129,0.13)');
        gCnt.addColorStop(1, 'rgba(16,185,129,0.01)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: platformTrend.labels,
                datasets: [
                    {
                        label: 'GMV Platform',
                        data: platformTrend.gmvArr,
                        borderColor: '#6366f1',
                        backgroundColor: gGmv,
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
                        data: platformTrend.countArr,
                        borderColor: '#10b981',
                        backgroundColor: gCnt,
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
                        backgroundColor: 'rgba(15,23,42,0.88)',
                        titleColor: '#94a3b8',
                        bodyColor: '#f1f5f9',
                        padding: 12,
                        cornerRadius: 10,
                        bodyFont: { size: 12, weight: 'bold' },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 }, maxTicksLimit: 10, maxRotation: 0 },
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
                        ticks: { color: '#10b981', font: { size: 10 }, stepSize: 1 },
                    },
                },
            },
        });

        // ══════════════════════════════════════════════════════════════
        // 2. HORIZONTAL BAR CHART – Merchant Performance
        // ══════════════════════════════════════════════════════════════
        const hasMerchData = merchantPerf.labels && merchantPerf.labels.length > 0;

        new Chart(document.getElementById('merchantPerformanceChart'), {
            type: 'bar',
            data: {
                labels: hasMerchData ? merchantPerf.labels : ['Belum ada data'],
                datasets: [{
                    label: 'GMV (Rp)',
                    data: hasMerchData ? merchantPerf.gmv : [0],
                    backgroundColor: palette.map(c => c + 'cc'),
                    borderColor: palette,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                indexAxis: 'y',   // horizontal bar
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (ctx) => ' ' + fmtRp(ctx.parsed.x) },
                        backgroundColor: 'rgba(15,23,42,0.88)',
                        bodyColor: '#f1f5f9',
                        padding: 10,
                        cornerRadius: 8,
                        bodyFont: { size: 12, weight: 'bold' },
                    },
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(148,163,184,0.1)' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            callback: (v) => Intl.NumberFormat('id-ID', { notation: 'compact', style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v),
                        },
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#475569', font: { size: 10, weight: '600' } },
                    },
                },
            },
        });

        // ══════════════════════════════════════════════════════════════
        // 3. DOUGHNUT CHART – Order Status Distribution
        // ══════════════════════════════════════════════════════════════
        const hasStatusData = orderStatus.labels && orderStatus.labels.length > 0;
        const statusLabels = hasStatusData ? orderStatus.labels : ['Belum ada data'];
        const statusValues = hasStatusData ? orderStatus.values : [1];
        const statusColors = statusLabels.map(l => statusPalette[l] || '#94a3b8');

        new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: statusColors,
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
                        enabled: hasStatusData,
                        callbacks: { label: (ctx) => ' ' + ctx.parsed + ' pesanan' },
                        backgroundColor: 'rgba(15,23,42,0.88)',
                        bodyColor: '#f1f5f9',
                        padding: 10,
                        cornerRadius: 8,
                    },
                },
            },
        });

        // Render status legend
        const statusLegendEl = document.getElementById('statusLegend');
        if (hasStatusData) {
            const total = statusValues.reduce((a, b) => a + b, 0);
            statusLabels.forEach((label, i) => {
                const pct = total > 0 ? Math.round(statusValues[i] / total * 100) : 0;
                statusLegendEl.innerHTML += `
                    <div class="flex items-center justify-between text-[10px]">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:${statusColors[i]}"></span>
                            <span class="text-slate-600 truncate">${label}</span>
                        </div>
                        <span class="font-bold text-slate-700 flex-shrink-0 ml-2">${statusValues[i]} <span class="text-slate-400 font-normal">(${pct}%)</span></span>
                    </div>`;
            });
        }

        // ══════════════════════════════════════════════════════════════
        // 4. AUTO-REFRESH KPI via fetch API
        // ══════════════════════════════════════════════════════════════
        const countdownEl = document.getElementById('admin-countdown');
        let secondsLeft   = REFRESH_SECONDS;

        function formatTime(s) {
            return `${Math.floor(s / 60)}:${(s % 60).toString().padStart(2, '0')}`;
        }

        async function refreshAdminKpi() {
            try {
                const res  = await fetch(kpiRefreshUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                });
                if (!res.ok) return;
                const data = await res.json();

                const fmt = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
                document.getElementById('admin-kpi-revenue').textContent    = fmt(data.total_revenue);
                document.getElementById('admin-kpi-commission').textContent = fmt(data.total_commission);
                document.getElementById('admin-kpi-orders').textContent     = data.total_orders;
                document.getElementById('admin-kpi-users').textContent      = data.total_users;
                document.getElementById('admin-kpi-active-shops').textContent = data.total_active_shops;
                document.getElementById('admin-kpi-pending').textContent    = data.pending_shops;

            } catch (e) {
                console.warn('Admin KPI refresh failed', e);
            } finally {
                secondsLeft = REFRESH_SECONDS;
            }
        }

        setInterval(() => {
            secondsLeft--;
            if (countdownEl) countdownEl.textContent = formatTime(secondsLeft);
            if (secondsLeft <= 0) {
                secondsLeft = REFRESH_SECONDS;
                refreshAdminKpi();
            }
        }, 1000);
    })();
    </script>
    @endpush
</x-app-layout>
