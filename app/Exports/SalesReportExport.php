<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected Shop $shop;
    protected Carbon $startDate;
    protected Carbon $endDate;

    public function __construct(Shop $shop, Carbon $startDate, Carbon $endDate)
    {
        $this->shop      = $shop;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Build the collection of rows for the spreadsheet.
     */
    public function collection()
    {
        $orders = Order::where('shop_id', $this->shop->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['customer', 'items.product', 'payment'])
            ->orderBy('created_at', 'asc')
            ->get();

        $rows = collect();
        $no   = 1;

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $rows->push([
                    'No'             => $no++,
                    'Tanggal'        => $order->created_at->format('d/m/Y'),
                    'No. Invoice'    => $order->invoice_number,
                    'Nama Pembeli'   => $order->customer->name ?? '-',
                    'Nama Produk'    => $item->product->name ?? '-',
                    'Qty'            => $item->qty,
                    'Harga Satuan'   => (float) $item->price,
                    'Subtotal Produk'=> (float) $item->subtotal,
                    'Ongkos Kirim'   => (float) $order->shipping_cost,
                    'Grand Total'    => (float) $order->grand_total,
                    'Kurir'          => $order->courier ?? '-',
                    'No. Resi'       => $order->tracking_number ?? '-',
                    'Metode Bayar'   => $order->payment->method ?? '-',
                    'Status'         => 'Selesai',
                ]);
            }
        }

        return $rows;
    }

    /**
     * Column headings for the spreadsheet.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Invoice',
            'Nama Pembeli',
            'Nama Produk',
            'Qty',
            'Harga Satuan (Rp)',
            'Subtotal Produk (Rp)',
            'Ongkos Kirim (Rp)',
            'Grand Total (Rp)',
            'Kurir',
            'No. Resi',
            'Metode Bayar',
            'Status',
        ];
    }

    /**
     * Apply styling to the worksheet.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Rekap Penjualan';
    }
}
