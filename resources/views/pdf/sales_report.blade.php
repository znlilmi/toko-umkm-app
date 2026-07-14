<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $shop->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .logo-sub {
            font-size: 9px;
            background-color: #f97316;
            color: white;
            padding: 1px 5px;
            border-radius: 3px;
            text-transform: uppercase;
            font-weight: bold;
            margin-left: 3px;
            vertical-align: middle;
        }
        .report-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
        }
        .subtitle {
            text-align: right;
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .divider {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .stats-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }
        .stats-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 5px;
        }
        .stats-card .label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .report-table th {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .report-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .report-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td>
                <span class="logo-text">toko</span><span class="logo-sub">umkm</span>
                <div style="font-size: 12px; color: #475569; margin-top: 5px; font-weight: bold;">{{ $shop->name }}</div>
            </td>
            <td>
                <div class="report-title">LAPORAN PENJUALAN</div>
                <div class="subtitle">
                    Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Stats Overview Cards -->
    <table class="stats-grid">
        <tr>
            <td style="width: 25%; padding-right: 10px;">
                <div class="stats-card">
                    <div class="label">Total Pesanan Sukses</div>
                    <div class="value">{{ $totalOrdersCount }}</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 10px; padding-left: 5px;">
                <div class="stats-card">
                    <div class="label">Jumlah Barang Terjual</div>
                    <div class="value">{{ $totalQty }} unit</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 5px; padding-left: 10px;">
                <div class="stats-card">
                    <div class="label">Omzet Penjualan</div>
                    <div class="value" style="color: #4f46e5;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 10px;">
                <div class="stats-card">
                    <div class="label">Total Pendapatan</div>
                    <div class="value" style="color: #10b981;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Table -->
    <h3 style="font-size: 13px; color: #1e293b; margin-bottom: 10px; border-left: 3px solid #4f46e5; padding-left: 8px;">Daftar Detail Transaksi Selesai</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 22%;">No. Invoice</th>
                <th>Nama Pembeli</th>
                <th class="text-right" style="width: 10%;">Item</th>
                <th class="text-right" style="width: 15%;">Total Belanja</th>
                <th class="text-right" style="width: 12%;">Ongkos Kirim</th>
                <th style="width: 15%;">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @if($orders->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada transaksi diselesaikan pada periode ini.</td>
                </tr>
            @else
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td style="font-family: monospace; font-size: 10px;">{{ $order->invoice_number }}</td>
                        <td>{{ $order->customer->name ?? 'Pelanggan' }}</td>
                        <td class="text-right">{{ $order->items->sum('qty') }}</td>
                        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        <td>
                            @if($order->payment)
                                <span style="text-transform: uppercase;">{{ $order->payment->payment_method }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Footer Summary Info -->
    <div class="footer">
        Laporan Penjualan Otomatis &bull; Dicetak Pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} &bull; TokoKita UMKM E-Commerce
    </div>

</body>
</html>
