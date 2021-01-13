<?php

namespace App\Exports;

use App\Model\view\VProductSales as ViewVProductSales;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesTransactionExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithColumnFormatting,
    ShouldAutoSize,
    WithStyles
{
    protected $dari_tgl;
    protected $sampai_tgl;

    function __construct($dari_tgl, $sampai_tgl)
    {
        $this->dari_tgl = $dari_tgl;
        $this->sampai_tgl = $sampai_tgl;
    }
    public function query()
    {
        return ViewVProductSales::whereBetween('sales_date', [$this->dari_tgl, $this->sampai_tgl])->orderBy('numerator', 'asc');
    }

    public function map($productSales): array
    {
        return [
            $productSales->no_kuitansi,
            $productSales->sales_date,
            $productSales->distributor_name,
            $productSales->product_name,
            $productSales->qty,
            $productSales->basic_selling_price,
            $productSales->total_selling_price,
            $productSales->profit
        ];
    }

    public function headings(): array
    {
        return [
            'NUMERATOR',
            'DATE',
            'DISTRIBUTOR',
            'PRODUCT',
            'QTY',
            'PRODUCT PRICE',
            'OMZET',
            'PROFIT'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // $styleArray = [
        //     'borders' => [
        //         'outline' => [
        //             'borderStyle' => Border::BORDER_THICK
        //         ]
        //     ]
        // ];
        // $styleCenter = array(
        //     'alignment' => array(
        //         'horizontal' => Alignment::HORIZONTAL_CENTER,
        //         'vertical' => Alignment::VERTICAL_CENTER
        //     )
        // );
        // $styleFont = array(
        //     'font' => array(
        //         'bold' => true,
        //         'size' => 11
        //     )
        // );
        // $sheet->getStyle('A1:H1')->applyFromArray($styleArray);
        // $sheet->getStyle('A1:H1')->applyFromArray($styleCenter);
        // $sheet->getStyle('A1:H1')->applyFromArray($styleFont);
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => array(
                'bold' => true,
                'size' => 11
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ),
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
        // return [
        //     // Style the first row as bold text.
        //     1    => ['font' => ['bold' => true]],
        //     // Styling a specific cell by coordinate.
        //     'B2' => ['font' => ['italic' => true]],

        //     // Styling an entire column.
        //     'C'  => ['font' => ['size' => 16]],
        // ];
    }
}
