<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 border border-slate-800">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-2 text-center md:text-left">
                <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-3.5 py-1 rounded-full border border-indigo-500/30 uppercase tracking-widest">Panel Administrator</span>
                <h1 class="text-3xl font-extrabold tracking-tight">Analisis & Moderasi Ulasan</h1>
                <p class="text-indigo-200 text-sm max-w-xl leading-relaxed">Pantau grafik sebaran kepuasan pelanggan, kelola rata-rata nilai produk, dan lakukan moderasi ulasan platform.</p>
            </div>
        </div>

        <!-- Upper Grid: Chart & Product Rating Averages -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Chart Card -->
            <div class="lg:col-span-5 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-1">
                        <span class="w-1 h-4 bg-indigo-600 rounded-full inline-block"></span>
                        Sebaran Rating Pelanggan
                    </h3>
                    <p class="text-[10px] text-slate-400">Distribusi nilai rating 1 sampai 5 bintang untuk seluruh produk</p>
                </div>
                <div class="relative mt-4" style="height: 200px;">
                    <canvas id="ratingDistributionChart"></canvas>
                </div>
            </div>

            <!-- Product Rating Average Table Card -->
            <div class="lg:col-span-7 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-1">
                        <span class="w-1 h-4 bg-indigo-600 rounded-full inline-block"></span>
                        Rata-Rata Rating Per Produk
                    </h3>
                    <p class="text-[10px] text-slate-400">Tabel rata-rata nilai kepuasan per produk aktif</p>
                </div>

                <div class="overflow-x-auto mt-4 flex-1">
                    <table class="w-full text-[11px] text-left text-slate-500">
                        <thead class="text-[10px] text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-4 py-2.5">Produk</th>
                                <th class="px-4 py-2.5">Toko</th>
                                <th class="px-4 py-2.5">Rata-Rata Rating</th>
                                <th class="px-4 py-2.5 text-center">Total Ulasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($productsRating as $product)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800 max-w-[150px] truncate">
                                        {{ $product->name }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-500 max-w-[120px] truncate">
                                        {{ $product->shop->name ?? 'Toko Terhapus' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="font-bold text-slate-700">{{ number_format($product->rating, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 text-center font-semibold text-slate-600">
                                        {{ $product->reviews_count }} ulasan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada data rating produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 mt-2 border-t border-slate-100">
                    {{ $productsRating->appends(request()->except('products_page'))->links() }}
                </div>
            </div>
        </div>

        <!-- Lower Panel: Moderation Table -->
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden p-6 md:p-8">
            <div class="mb-6">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-rose-500 rounded-full inline-block"></span>
                    Daftar Ulasan Terbaru (Perlu Moderasi)
                </h3>
                <p class="text-xs text-slate-400 mt-0.5 ml-4">Gunakan tombol hapus ulasan jika ulasan melanggar pedoman platform atau mengandung konten sensitif.</p>
            </div>

            @if($reviews->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.12 2.83 2.78 2.83h1.37l3.52 3.52c.22.22.58.22.8 0V17.1c3.15-.15 5.75-2.6 5.75-5.85a5.99 5.99 0 00-6-6 6 6 0 00-6 6v.26z" />
                    </svg>
                    <p class="text-sm">Belum ada ulasan yang masuk di platform.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Produk / Toko</th>
                                <th class="px-6 py-4">Rating</th>
                                <th class="px-6 py-4">Komentar</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($reviews as $review)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">
                                            {{ $review->orderItem->order->customer->name ?? 'Pelanggan Toko' }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $review->orderItem->order->customer->email ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800 text-sm">
                                            {{ $review->product->name }}
                                        </div>
                                        <div class="text-xs text-indigo-600">
                                            {{ $review->product->shop->name ?? 'Toko Terhapus' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="text-xs text-slate-400 ms-1">({{ $review->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate text-slate-600 font-medium">
                                        {{ $review->comment ?? 'Tidak ada komentar tertulis.' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/menghilangkan ulasan ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition border border-rose-100 flex items-center gap-1.5 text-xs font-semibold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $reviews->appends(request()->except('reviews_page'))->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const distData = @json(array_values($distribution));
        const ctx = document.getElementById('ratingDistributionChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['★ 1', '★ 2', '★ 3', '★ 4', '★ 5'],
                datasets: [{
                    label: 'Jumlah Ulasan',
                    data: distData,
                    backgroundColor: [
                        'rgba(244, 63, 94, 0.8)',   // 1 Star: Rose
                        'rgba(249, 115, 22, 0.8)',  // 2 Stars: Orange
                        'rgba(234, 179, 8, 0.8)',   // 3 Stars: Yellow
                        'rgba(168, 85, 247, 0.8)',  // 4 Stars: Purple
                        'rgba(16, 185, 129, 0.8)'   // 5 Stars: Emerald
                    ],
                    borderColor: [
                        '#f43f5e', '#f97316', '#eab308', '#a855f7', '#10b981'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 10,
                        cornerRadius: 8,
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1'
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 9 },
                            color: '#94a3b8',
                            stepSize: 1
                        }
                    }
                }
            }
        });
    })();
    </script>
    @endpush
</x-app-layout>
