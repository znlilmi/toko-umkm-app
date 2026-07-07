<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Moderasi & Verifikasi Toko</h1>
            <p class="text-sm text-slate-400">Verifikasi pengajuan pendaftaran toko baru dan kelola status aktif toko pada platform.</p>
        </div>

        <!-- Filter Tab Links -->
        <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-px">
            @php
                $statuses = [
                    'all' => 'Semua Toko',
                    'pending' => 'Menunggu Verifikasi',
                    'active' => 'Aktif',
                    'rejected' => 'Ditolak',
                    'suspended' => 'Ditangguhkan'
                ];
            @endphp
            @foreach($statuses as $key => $label)
                <a href="{{ route('admin.shops.index', ['status' => $key]) }}" class="px-4 py-2 text-xs font-semibold rounded-t-xl transition border-b-2 {{ request('status', 'pending') == $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Shops List Table -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($shops->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <p class="text-sm">Tidak ada toko dengan kriteria status ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Nama Toko</th>
                                <th class="px-6 py-4">Pemilik (Merchant)</th>
                                <th class="px-6 py-4">Tanggal Daftar</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($shops as $shop)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm mb-0.5">{{ $shop->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $shop->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-slate-700 text-xs font-medium">{{ $shop->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">{{ $shop->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $shop->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($shop->status === 'pending')
                                            <span class="text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full">Pending</span>
                                        @elseif($shop->status === 'active')
                                            <span class="text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full">Aktif</span>
                                        @elseif($shop->status === 'rejected')
                                            <span class="text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full">Ditolak</span>
                                        @elseif($shop->status === 'suspended')
                                            <span class="text-[9px] font-bold bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full">Suspend</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.shops.show', $shop->id) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                                            Moderasi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $shops->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
