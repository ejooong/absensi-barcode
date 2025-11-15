<?php
// app/Exports/PesertaExport.php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PesertaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return Peserta::all()->map(function($peserta, $index) {
            return [
                'No' => $index + 1,
                'Nama' => $peserta->nama,
                'Jabatan' => $peserta->jabatan ?? '-',
                'Kelas' => $peserta->kelas ?? '-',
                'Barcode' => $peserta->barcode_data,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 
            'Nama', 
            'Jabatan', 
            'Kelas',
            'Barcode Data', 
            'QR Code'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,    // No
            'B' => 30,   // Nama
            'C' => 25,   // Jabatan
            'D' => 15,   // Kelas
            'E' => 25,   // Barcode
            'F' => 20,   // QR Code
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2C3E50']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Center align
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:F100')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $peserta = Peserta::all();
                $row = 2;

                foreach ($peserta as $p) {
                    // Set row height untuk QR code
                    $sheet->getRowDimension($row)->setRowHeight(80);

                    if (!empty($p->barcode_data)) {
                        // Generate QR code dan simpan sementara
                        $qrCode = QrCode::format('png')
                            ->size(150)
                            ->generate($p->barcode_data);
                        
                        // Simpan sementara di storage
                        $tempPath = storage_path('app/temp/qr_' . $p->id . '_' . time() . '.png');
                        
                        // Pastikan directory exists
                        if (!file_exists(dirname($tempPath))) {
                            mkdir(dirname($tempPath), 0755, true);
                        }
                        
                        file_put_contents($tempPath, $qrCode);
                        
                        // Insert QR code ke Excel
                        $drawing = new Drawing();
                        $drawing->setName('QR_' . $p->id);
                        $drawing->setDescription('QR Code ' . $p->nama);
                        $drawing->setPath($tempPath);
                        
                        // Set ukuran - lebih kecil dari sebelumnya
                        $drawing->setHeight(60);
                        $drawing->setWidth(60);
                        
                        $drawing->setCoordinates('F' . $row);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(10);
                        
                        $drawing->setWorksheet($sheet);
                        
                        // Hapus file temporary setelah digunakan
                        register_shutdown_function(function() use ($tempPath) {
                            if (file_exists($tempPath)) {
                                unlink($tempPath);
                            }
                        });
                    } else {
                        $sheet->setCellValue('F' . $row, 'Tidak ada barcode');
                        $sheet->getStyle('F' . $row)->getAlignment()
                              ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                              ->setVertical(Alignment::VERTICAL_CENTER);
                    }
                    
                    $row++;
                }

                // Set borders
                $lastRow = $peserta->count() + 1;
                $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'DDDDDD'],
                        ],
                    ],
                ]);
            }
        ];
    }
}