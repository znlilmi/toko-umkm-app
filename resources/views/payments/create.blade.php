<x-app-layout>
    <div class="py-6 max-w-xl mx-auto space-y-6" x-data="{ method: 'Transfer BCA' }">
        <div>
            <a href="{{ route('orders.show', $order->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Detail Pesanan</span>
            </a>
        </div>

        <!-- Payment Instructions Box -->
        <div class="bg-indigo-900 text-white rounded-3xl p-6 md:p-8 shadow-sm relative overflow-hidden">
            <!-- Decorative light blur -->
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
            
            <h3 class="font-bold text-lg mb-4 flex items-center space-x-2 relative z-10">
                <svg class="w-6 h-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5" />
                </svg>
                <span>Instruksi Pembayaran</span>
            </h3>

            <!-- Dynamic content based on selected method -->
            <div class="space-y-4 text-sm text-indigo-100 relative z-10">
                <!-- Bank Transfer Instructions -->
                <div x-show="method.startsWith('Transfer')" class="space-y-4">
                    <p>Silakan lakukan transfer sesuai dengan total tagihan Anda ke salah satu rekening berikut:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-white/10 p-4 rounded-xl border border-white/10">
                        <div x-show="method === 'Transfer BCA' || method === 'Transfer Bank Lain'">
                            <span class="text-xs text-indigo-300 block font-semibold">Bank BCA</span>
                            <span class="font-bold text-white block">123-456-7890</span>
                            <span class="text-xs text-indigo-200">a/n PT TokoKita Jaya</span>
                        </div>
                        <div x-show="method === 'Transfer Mandiri' || method === 'Transfer Bank Lain'">
                            <span class="text-xs text-indigo-300 block font-semibold">Bank Mandiri</span>
                            <span class="font-bold text-white block">987-654-3210-234</span>
                            <span class="text-xs text-indigo-200">a/n PT TokoKita Jaya</span>
                        </div>
                    </div>
                </div>

                <!-- QRIS Instructions -->
                <div x-show="method === 'QRIS (Gopay/OVO/Dana)'" class="space-y-4 flex flex-col items-center">
                    <p class="text-center w-full">Silakan pindai/scan QRIS di bawah ini menggunakan aplikasi E-Wallet atau M-Banking Anda:</p>
                    <div class="bg-white p-3 rounded-2xl w-48 shadow-lg flex items-center justify-center">
                        <img src="{{ asset('images/qris_mockup.png') }}" alt="QRIS Mockup" class="w-full h-auto rounded-lg">
                    </div>
                    <span class="text-[10px] text-indigo-200 font-bold bg-white/15 px-3.5 py-1.5 rounded-full uppercase tracking-wider">NMID: ID102938475682</span>
                </div>

                <!-- E-Wallet Instructions -->
                <div x-show="['GoPay', 'OVO', 'Dana', 'ShopeePay'].includes(method)" class="space-y-4">
                    <p>Silakan kirim saldo E-Wallet Anda ke nomor akun merchant resmi kami:</p>
                    <div class="bg-white/10 p-4 rounded-xl border border-white/10 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-indigo-300 font-bold" x-text="method"></span>
                            <span class="font-bold text-white text-base">0812-3456-7890</span>
                        </div>
                        <div class="text-xs text-indigo-200">
                            Penerima: <strong>a/n TokoKita Official</strong>
                        </div>
                    </div>
                    <p class="text-xs text-indigo-200 leading-relaxed">Sertakan nomor Invoice <strong class="text-white">{{ $order->invoice_number }}</strong> pada bagian catatan transfer Anda untuk verifikasi instan.</p>
                </div>

                <div class="text-xs text-indigo-200 bg-white/5 p-3 rounded-lg border border-white/5">
                    <strong>PENTING:</strong> Unggah bukti transaksi Anda di form di bawah ini setelah pembayaran berhasil dilakukan agar segera dikonfirmasi oleh penjual.
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-6">Konfirmasi Pembayaran</h1>
            
            <div class="mb-6 p-4 bg-slate-50 border rounded-2xl flex justify-between items-center text-sm">
                <div>
                    <span class="text-slate-400 block text-xs">No. Invoice</span>
                    <span class="font-bold text-slate-700">{{ $order->invoice_number }}</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block text-xs">Total Tagihan</span>
                    <span class="font-extrabold text-indigo-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('payments.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Payment Method Select -->
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-slate-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" x-model="method" required class="w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-2.5 text-sm bg-white">
                        <optgroup label="Bank Transfer">
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Transfer Bank Lain">Transfer Bank Lain</option>
                        </optgroup>
                        <optgroup label="Instant QRIS">
                            <option value="QRIS (Gopay/OVO/Dana)">QRIS (Gopay/OVO/Dana)</option>
                        </optgroup>
                        <optgroup label="Direct E-Wallet">
                            <option value="GoPay">GoPay</option>
                            <option value="OVO">OVO</option>
                            <option value="Dana">Dana</option>
                            <option value="ShopeePay">ShopeePay</option>
                        </optgroup>
                    </select>
                    @error('payment_method')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Proof of Payment Upload -->
                <div>
                    <label for="proof_of_payment" class="block text-sm font-semibold text-slate-700 mb-2">Unggah Bukti Pembayaran (Gambar max 2MB)</label>
                    <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-2.5">
                    @error('proof_of_payment')
                        <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-lg shadow-indigo-100">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
