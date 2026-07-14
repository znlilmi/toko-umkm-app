<?php

namespace App\Exports;

use App\Models\Shop;
use App\Models\StockMutation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMutationExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
        $mutations = StockMutation::whereHas('product', function ($q) {
            $q->where('shop_id', $this->shop->id);
        })
            ->with('product.categories')
            ->orderBy('created_at', 'desc')
            ->get();

        $rows = collect();
        $no   = 1;

        foreach ($mutations as $mutation) {
            $categories = $mutation->product
                ? $mutation->product->categories->pluck('name')->implode(', ')
                : '-';

            $rows->push([
                'No'            => $no++,
                'Waktu'         => $mutation->created_at->format('d/m/Y H:i'),
                'ID Produk'     => $mutation->product_id,
                'Nama Produk'   => $mutation->product->name ?? '-',
                'Kategori'      => $categories,
                'Tipe Mutasi'   => strtoupper($mutation->type),
                'Qty Perubahan' => $mutation->type === 'OUT' ? -abs($mutation->qty) : abs($mutation->qty),
                'Keterangan'    => $mutation->description ?? '-',
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
            'Waktu Kejadian',
            'ID Produk',
            'Nama Produk',
            'Kategori',
            'Tipe Mutasi',
            'Qty Perubahan',
            'Keterangan',
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
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE11D48']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Mutasi Stok';
    }
}
