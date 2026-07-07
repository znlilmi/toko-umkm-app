<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto space-y-6">
        <!-- Back Link -->
        <div>
            <a href="{{ route('admin.shops.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Daftar Moderasi</span>
            </a>
        </div>

        <!-- Shop Detail Profile Card -->
        <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
            <!-- Banner area -->
            <div class="h-48 bg-slate-100 relative">
                @if($shop->banner)
                    <img src="{{ asset('storage/' . $shop->banner) }}" alt="Banner" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-slate-200 to-slate-100"></div>
                @endif
                
                <!-- Logo placement -->
                <div class="absolute -bottom-8 left-8">
                    @if($shop->logo)
                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-sm bg-white">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-slate-300 border-4 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold text-2xl">
                            {{ substr($shop->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details Block -->
            <div class="pt-12 px-8 pb-8 space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $shop->name }}</h1>
                        <span class="text-xs text-slate-400 block font-mono mt-0.5">Slug: {{ $shop->slug }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Status Saat Ini</span>
                        @if($shop->status === 'pending')
                            <span class="text-xs font-bold bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1 rounded-full">Menunggu Verifikasi</span>
                        @elseif($shop->status === 'active')
                            <span class="text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full">Aktif / Terverifikasi</span>
                        @elseif($shop->status === 'rejected')
                            <span class="text-xs font-bold bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1 rounded-full">Pendaftaran Ditolak</span>
                        @elseif($shop->status === 'suspended')
                            <span class="text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">Ditangguhkan</span>
                        @endif
                    </div>
                </div>

                <!-- Parameters list -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-1">Pemilik (Merchant)</span>
                            <span class="font-bold text-slate-700 block">{{ $shop->user->name }}</span>
                            <span class="text-xs text-slate-500 block">{{ $shop->user->email }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-1">Alamat Asal / Pengiriman</span>
                            <p class="text-slate-600 leading-relaxed">{{ $shop->address }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-1">Saldo Toko</span>
                            <span class="text-xl font-extrabold text-slate-800 block">Rp {{ number_format($shop->balance, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-1">Kota / RajaOngkir ID</span>
                            <span class="font-semibold text-slate-700 block">ID Kota: {{ $shop->city_id }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-1">Terdaftar Sejak</span>
                            <span class="text-slate-600 block">{{ $shop->created_at->format('d M Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-t pt-5 border-slate-100">
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-2">Deskripsi Toko</span>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $shop->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>
        </div>

        <!-- Moderation Actions Box -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-6">Tindakan Moderasi</h3>
            
            <div class="flex flex-col sm:flex-row gap-4">
                @if($shop->status === 'pending')
                    <!-- Verify / Approve -->
                    <form action="{{ route('admin.shops.verify', $shop->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                            Setujui & Aktifkan Toko
                        </button>
                    </form>

                    <!-- Reject -->
                    <form action="{{ route('admin.shops.reject', $shop->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan toko ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-3 px-6 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-semibold rounded-xl transition text-sm">
                            Tolak Pendaftaran
                        </button>
                    </form>
                @endif

                @if($shop->status === 'active')
                    <!-- Suspend -->
                    <form action="{{ route('admin.shops.suspend', $shop->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menangguhkan (suspend) toko ini? Toko tidak akan bisa melakukan penjualan.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-3 px-6 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                            Suspend / Tangguhkan Toko
                        </button>
                    </form>
                @endif
                
                @if($shop->status === 'suspended')
                    <!-- Reactivate via verify -->
                    <form action="{{ route('admin.shops.verify', $shop->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                            Reaktifkan / Aktifkan Kembali Toko
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
