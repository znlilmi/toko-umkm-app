<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }
        .logo-sub {
            font-size: 10px;
            background-color: #f97316;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: bold;
            margin-left: 5px;
            vertical-align: middle;
        }
        .invoice-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .meta-table td {
            vertical-align: top;
            width: 50%;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .info-block {
            margin-bottom: 15px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 12px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right {
            text-align: right;
        }
        .summary-table {
            width: 45%;
            margin-left: 55%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px;
        }
        .summary-table .label {
            color: #64748b;
        }
        .summary-table .value {
            font-weight: bold;
            text-align: right;
        }
        .summary-table .total-row td {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 16px;
            color: #4f46e5;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-success { background-color: #ecfdf5; color: #065f46; }
        .badge-warning { background-color: #fffbeb; color: #92400e; }
        .badge-danger { background-color: #fef2f2; color: #991b1b; }
        .badge-info { background-color: #eff6ff; color: #1e40af; }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td>
                <span class="logo-text">toko</span><span class="logo-sub">umkm</span>
            </td>
            <td class="invoice-title">
                INVOICE
            </td>
        </tr>
    </table>

    <!-- Meta Details Section -->
    <table class="meta-table">
        <tr>
            <td>
                <div class="info-block">
                    <div class="section-title">Diterbitkan Oleh</div>
                    <strong>{{ $order->shop->name ?? 'Toko Kita' }}</strong><br>
                    Alamat Toko: {{ $order->shop->address ?? '-' }}
                </div>
                <div class="info-block">
                    <div class="section-title">Tujuan Pengiriman</div>
                    {!! nl2br(e($order->shipping_address)) !!}
                </div>
            </td>
            <td style="padding-left: 40px;">
                <div class="info-block">
                    <div class="section-title">Detail Invoice</div>
                    <table style="width: 100%; font-size: 13px;">
                        <tr>
                            <td style="color: #64748b; padding: 2px 0;">No. Invoice:</td>
                            <td style="font-weight: bold; padding: 2px 0;">{{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 2px 0;">Tanggal:</td>
                            <td style="padding: 2px 0;">{{ $order->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 2px 0;">Kurir Ekspedisi:</td>
                            <td style="padding: 2px 0; text-transform: uppercase;">{{ $order->courier }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; padding: 2px 0;">Status Pesanan:</td>
                            <td style="padding: 2px 0;">
                                @php
                                    $statusLabels = [
                                        'pending_payment' => ['badge-warning', 'Menunggu Pembayaran'],
                                        'pending_confirmation' => ['badge-info', 'Menunggu Verifikasi'],
                                        'processing' => ['badge-info', 'Diproses'],
                                        'shipped' => ['badge-info', 'Dikirim'],
                                        'completed' => ['badge-success', 'Selesai'],
                                        'cancelled' => ['badge-danger', 'Dibatalkan']
                                    ];
                                    $label = $statusLabels[$order->status] ?? ['badge-info', $order->status];
                                @endphp
                                <span class="badge {{ $label[0] }}">{{ $label[1] }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Order Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th class="text-right" style="width: 15%;">Harga</th>
                <th class="text-right" style="width: 10%;">Jumlah</th>
                <th class="text-right" style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product->name ?? 'Produk Terhapus' }}</strong>
                        @if($item->product && $item->product->weight)
                            <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                Berat: {{ $item->product->weight }} gram
                            </div>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pricing Summary Section -->
    <table class="summary-table">
        <tr>
            <td class="label">Total Harga ({{ $order->items->sum('qty') }} Barang)</td>
            <td class="value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Ongkos Kirim</td>
            <td class="value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Grand Total</td>
            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Payment Detail Footer -->
    <div style="margin-top: 30px; font-size: 13px;">
        <div class="section-title" style="margin-bottom: 5px;">Metode Pembayaran</div>
        @if($order->payment)
            <strong>{{ strtoupper($order->payment->payment_method) }}</strong> - 
            Status: 
            @php
                $payStatusLabels = [
                    'pending' => ['badge-warning', 'Menunggu Konfirmasi'],
                    'paid' => ['badge-success', 'Lunas'],
                    'expired' => ['badge-danger', 'Kedaluwarsa'],
                    'failed' => ['badge-danger', 'Gagal']
                ];
                $payLabel = $payStatusLabels[$order->payment->payment_status] ?? ['badge-info', $order->payment->payment_status];
            @endphp
            <span class="badge {{ $payLabel[0] }}" style="font-size: 9px; padding: 2px 6px;">{{ $payLabel[1] }}</span>
            @if($order->payment->paid_at)
                <span style="color: #64748b; font-size: 11px; margin-left: 10px;">Lunas Pada: {{ \Carbon\Carbon::parse($order->payment->paid_at)->format('d F Y, H:i') }}</span>
            @endif
        @else
            <strong>Belum ada info pembayaran.</strong>
        @endif
    </div>

    <!-- Thank you footer -->
    <div class="footer">
        Terima kasih telah berbelanja di Toko UMKM TokoKita.<br>
        Dokumen ini sah dan diterbitkan secara elektronik oleh sistem TokoKita.
    </div>

</body>
</html>
