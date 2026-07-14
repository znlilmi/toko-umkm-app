<?php

namespace App\Exports;

use App\Models\Review;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReviewExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected Shop $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Build the collection of rows for the spreadsheet.
     */
    public function collection()
    {
        $reviews = Review::whereHas('product', function ($q) {
            $q->where('shop_id', $this->shop->id);
        })
            ->with(['product', 'orderItem.order.customer'])
            ->latest()
            ->get();

        $rows = collect();
        $no   = 1;

        foreach ($reviews as $review) {
            $rows->push([
                'No'             => $no++,
                'Tanggal Ulasan' => $review->created_at->format('d/m/Y H:i'),
                'Nama Produk'    => $review->product->name ?? '-',
                'Harga Produk'   => (float) ($review->product->price ?? 0),
                'Nama Pembeli'   => $review->orderItem?->order?->customer?->name ?? '-',
                'Rating'         => $review->rating,
                'Bintang'        => str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating),
                'Komentar'       => $review->comment ?? '(Tanpa komentar)',
                'No. Invoice'    => $review->orderItem?->order?->invoice_number ?? '-',
            ]);
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
            'Tanggal Ulasan',
            'Nama Produk',
            'Harga Produk (Rp)',
            'Nama Pembeli',
            'Rating (Angka)',
            'Rating (Bintang)',
            'Komentar / Ulasan',
            'No. Invoice',
        ];
    }

    /**
     * Apply styling to the worksheet.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF59E0B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Ulasan Pelanggan';
    }
}
