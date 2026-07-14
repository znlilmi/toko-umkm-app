<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Ulasan & Umpan Balik Pelanggan</h1>
                <p class="text-sm text-slate-400">Pantau ulasan dari pelanggan untuk terus meningkatkan kualitas produk Anda.</p>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Rata-rata Rating Toko</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ number_format($averageRating, 1) }}</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-amber-400 fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.107-.175-.27-.3-.464-.348a.488.488 0 00-.518.232L9.227 5.727l-2.5.37a.494.494 0 00-.388.43.488.488 0 00.177.472l1.91 1.833-1.076 3.018a.495.495 0 00.323.593.493.493 0 00.528-.15l2.45-1.927 2.45 1.926c.15.118.35.14.522.057.172-.083.284-.258.293-.45l.1-2.924 2.1-2.015a.493.493 0 00.138-.507.495.495 0 00-.356-.343l-2.91-.43-1.282-2.612z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Ulasan Masuk</span>
                    <span class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalReviews }} Ulasan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($reviews->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.12 2.83 2.78 2.83h1.37l3.52 3.52c.22.22.58.22.8 0V17.1c3.15-.15 5.75-2.6 5.75-5.85a5.99 5.99 0 00-6-6 6 6 0 00-6 6v.26z" />
                    </svg>
                    <p class="text-sm">Toko Anda belum menerima ulasan produk dari pembeli.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Pembeli</th>
                                <th class="px-6 py-4">Rating</th>
                                <th class="px-6 py-4">Ulasan</th>
                                <th class="px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($reviews as $review)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">
                                            {{ $review->product->name }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            Harga: Rp {{ number_format($review->product->price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-700 text-xs">
                                            {{ $review->orderItem->order->customer->name ?? 'Pelanggan Toko UMKM' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="text-xs text-slate-400 ms-1">({{ $review->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-sm truncate text-slate-600 text-sm">
                                        {{ $review->comment ?? 'Tidak ada komentar tertulis.' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
