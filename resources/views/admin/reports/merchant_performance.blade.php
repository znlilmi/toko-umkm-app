<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 border border-slate-800">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-2 text-center md:text-left">
                <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-3.5 py-1 rounded-full border border-indigo-500/30 uppercase tracking-widest">Panel Administrator</span>
                <h1 class="text-3xl font-extrabold tracking-tight">Performa & Omzet Penjual</h1>
                <p class="text-indigo-200 text-sm max-w-xl leading-relaxed">Bandingkan total nilai transaksi selesai bulanan antar pemilik toko UMKM dalam periode 3 bulan terakhir secara visual.</p>
            </div>
        </div>

        <!-- Upper Panel: Grouped Bar Chart -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <span class="w-1 h-5 bg-indigo-600 rounded-full inline-block"></span>
                        Perbandingan Omzet Antar Penjual (3 Bulan Terakhir)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 ml-3">Distribusi nilai omzet bulanan dalam Rupiah (Rp) per merchant</p>
                </div>
                <div class="flex items-center gap-3 text-[10px] text-slate-500 font-semibold ml-3">
                    @foreach($monthLabels as $idx => $label)
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm flex-shrink-0" style="background: {{ ['rgba(99, 102, 241, 0.85)', 'rgba(249, 115, 22, 0.85)', 'rgba(16, 185, 129, 0.85)'][$idx] }}"></span>
                            {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="merchantPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Lower Panel: Summary Table -->
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden p-6 md:p-8">
            <div class="mb-6">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full inline-block"></span>
                    Tabel Ringkasan Performa Merchant
                </h3>
                <p class="text-xs text-slate-400 mt-0.5 ml-4">Rincian omzet bulanan, akumulasi total omzet, dan total transaksi sukses untuk masing-masing penjual.</p>
            </div>

            @if(empty($merchantPerformance))
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72" />
                    </svg>
                    <p class="text-sm">Belum ada merchant/toko yang terdaftar.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Toko / Pemilik</th>
                                @foreach($monthLabels as $label)
                                    <th class="px-6 py-4 text-right">{{ $label }}</th>
                                @endforeach
                                <th class="px-6 py-4 text-right bg-indigo-50/50 text-indigo-700 font-bold">Total Omzet</th>
                                <th class="px-6 py-4 text-center">Total Pesanan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($merchantPerformance as $perf)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">
                                            {{ $perf['name'] }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            Pemilik: {{ $perf['owner'] }}
                                        </div>
                                    </td>
                                    @foreach($months as $month)
                                        <td class="px-6 py-4 text-right font-medium text-slate-700">
                                            Rp {{ number_format($perf['monthly_revenue'][$month], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 text-right font-extrabold text-indigo-700 bg-indigo-50/30">
                                        Rp {{ number_format($perf['total_revenue'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-600">
                                        <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full">
                                            {{ $perf['total_orders'] }} pesanan
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const labels = @json($chartLabels);
        const datasets = @json($chartDatasets);

        const ctx = document.getElementById('merchantPerformanceChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.92)',
                        padding: 12,
                        cornerRadius: 10,
                        titleColor: '#ffffff',
                        bodyColor: '#f1f5f9',
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, weight: 'bold' },
                            color: '#475569'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8',
                            callback: function (value) {
                                if (value >= 1e6) {
                                    return 'Rp ' + (value / 1e6) + ' Jt';
                                }
                                if (value >= 1e3) {
                                    return 'Rp ' + (value / 1e3) + ' Rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    })();
    </script>
    @endpush
</x-app-layout>
