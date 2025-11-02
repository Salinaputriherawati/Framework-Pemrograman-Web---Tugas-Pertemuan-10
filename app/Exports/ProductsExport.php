<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Unit',
            'Category',
            'Description',
            'Stock',
            'Supplier',
            'Created At',
            'Updated At',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert 3 rows di atas heading
                $sheet->insertNewRowBefore(1, 3);

                $firstProduct = \App\Models\Product::orderBy('created_at', 'asc')->first();
                $lastProduct  = \App\Models\Product::orderBy('created_at', 'desc')->first();

                $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : '';
                $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : '';

                // Set value judul
                $sheet->setCellValue('A1', 'PT. MILKO');
                $sheet->setCellValue('A2', 'Rekap Mutasi Stok Bulanan');
                $sheet->setCellValue('A3', 'Periode: ' . $startDate . ' s/d ' . $endDate);


                // Merge cell judul (sesuaikan sampai kolom terakhir datamu, misal kolom I)
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');

                // Styling judul
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(11);

                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                // Auto-size kolom
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells('A' . ($lastRow + 2) . ':I' . ($lastRow + 2)); // catatan kaki
                $sheet->setCellValue('A' . ($lastRow + 2), 'Data ini bersifat rahasia. Dicetak pada: ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A' . ($lastRow + 2))->getAlignment()->setWrapText(true);

                $sheet->mergeCells('A' . ($lastRow + 4) . ':I' . ($lastRow + 7)); // tanda tangan
                $sheet->setCellValue('A' . ($lastRow + 4), "Diketahui\n\nKepala Logistik\nSalina Putri Herawati");
                $sheet->getStyle('A' . ($lastRow + 4))->getAlignment()->setWrapText(true);
            },
        ];
    }
}
