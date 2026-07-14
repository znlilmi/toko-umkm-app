<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Komisi & Performa UMKM - TokoKita</title>
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
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 5px;
        }
        .stats-card .label {
            font-size: 9px;
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
            background-color: #1e293b;
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
        .status-badge {
            padding: 1px 5px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-suspended { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td>
                <span class="logo-text">toko</span><span class="logo-sub">umkm</span>
                <div style="font-size: 11px; color: #475569; margin-top: 5px; font-weight: bold;">Laporan Konsolidasi Platform Fee</div>
            </td>
            <td>
                <div class="report-title">LAPORAN KOMISI & PERFORMA</div>
                <div class="subtitle">
                    Tanggal Cetak: {{ $date->format('d M Y, H:i') }}
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
                    <div class="label">Total Toko UMKM</div>
                    <div class="value">{{ $totalShops }} Toko</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 10px; padding-left: 5px;">
                <div class="stats-card">
                    <div class="label">Total Transaksi</div>
                    <div class="value">{{ $totalCompletedOrdersCount }} Transaksi</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 5px; padding-left: 10px;">
                <div class="stats-card">
                    <div class="label">Total GMV Platform</div>
                    <div class="value" style="color: #1e293b;">Rp {{ number_format($totalPlatformGmv, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 10px;">
                <div class="stats-card" style="background-color: #eef2ff; border-color: #c7d2fe;">
                    <div class="label" style="color: #4f46e5;">Komisi Platform (5%)</div>
                    <div class="value" style="color: #4f46e5; font-size: 16px;">Rp {{ number_format($totalPlatformCommission, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Table -->
    <h3 style="font-size: 13px; color: #1e293b; margin-bottom: 10px; border-left: 3px solid #1e293b; padding-left: 8px;">Daftar Performa & Kontribusi Komisi Per Merchant</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th>Nama Toko</th>
                <th>Pemilik</th>
                <th>Tanggal Bergabung</th>
                <th class="text-right" style="width: 10%;">Transaksi</th>
                <th class="text-right" style="width: 18%;">GMV Toko</th>
                <th class="text-right" style="width: 18%;">Komisi Platform (5%)</th>
                <th style="width: 12%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($merchantData as $merchant)
                <tr>
                    <td><strong>{{ $merchant['name'] }}</strong></td>
                    <td>{{ $merchant['owner'] }}</td>
                    <td>{{ $merchant['joined_date'] }}</td>
                    <td class="text-right">{{ $merchant['orders_count'] }}</td>
                    <td class="text-right">Rp {{ number_format($merchant['gmv'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #4f46e5;">Rp {{ number_format($merchant['commission'], 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @if($merchant['status'] === 'active')
                            <span class="status-badge status-active">Aktif</span>
                        @elseif($merchant['status'] === 'pending')
                            <span class="status-badge status-pending">Menunggu</span>
                        @else
                            <span class="status-badge status-suspended">Ditangguhkan</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Summary Info -->
    <div class="footer">
        Laporan Keuangan Konsolidasi Global &bull; TokoKita UMKM E-Commerce Platform &bull; Bersama Memajukan UMKM Lokal
    </div>

</body>
</html>
