<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Kritis - {{ $shop->name }}</title>
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
            color: #b91c1c; /* Red alert color for critical stock */
        }
        .subtitle {
            text-align: right;
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .divider {
            border-bottom: 2px solid #f87171;
            margin-bottom: 20px;
        }
        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 12px;
            color: #991b1b;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 12px;
            border-radius: 0 8px 8px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .report-table th {
            background-color: #ef4444;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .report-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .report-table tr:nth-child(even) {
            background-color: #fff5f5;
        }
        .stock-badge {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #fecaca;
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
                <div class="report-title">LAPORAN STOK KRITIS</div>
                <div class="subtitle">
                    Tanggal Cetak: {{ $date->format('d M Y, H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="alert-box">
        PERINGATAN: Produk di bawah ini memiliki stok tersisa kurang dari atau sama dengan 5 unit. Harap segera lakukan restock untuk menghindari pembatalan pesanan otomatis atau kekosongan stok toko.
    </div>

    <!-- Main Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 10%;">ID Produk</th>
                <th>Nama Produk</th>
                <th style="width: 25%;">Kategori</th>
                <th class="text-right" style="width: 15%;">Harga Satuan</th>
                <th class="text-right" style="width: 12%;">Berat (Gram)</th>
                <th class="text-right" style="width: 12%;">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @if($products->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center; color: #059669; background-color: #ecfdf5; font-weight: bold; padding: 20px;">
                        Luar Biasa! Tidak ada produk dengan stok kritis saat ini. Semua produk terdistribusi dengan baik.
                    </td>
                </tr>
            @else
                @foreach($products as $product)
                    <tr>
                        <td style="font-family: monospace; font-size: 10px; color: #64748b;">PRD-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td>
                            {{ $product->categories->pluck('name')->implode(', ') ?: 'Kategori Lainnya' }}
                        </td>
                        <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($product->weight, 0, ',', '.') }} g</td>
                        <td class="text-right">
                            <span class="stock-badge">{{ $product->stock }} unit</span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Footer Summary Info -->
    <div class="footer">
        Laporan Stok Kritis Otomatis &bull; TokoKita UMKM E-Commerce &bull; Pastikan Sinkronisasi Stok Fisik
    </div>

</body>
</html>
