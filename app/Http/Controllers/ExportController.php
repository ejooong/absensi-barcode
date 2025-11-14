<?php
// app/Http/Controllers/ExportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Peserta;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use App\Exports\PesertaExport;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExportController extends Controller
{
    // Export Absensi to Excel
    public function exportAbsensiExcel(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $kelas = $request->get('kelas'); // Hapus kelompok

        return Excel::download(new AbsensiExport($tanggal, $kelas), 
            'laporan-absensi-' . $tanggal . '.xlsx');
    }

    // Export Absensi to PDF
    public function exportAbsensiPDF(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $kelas = $request->get('kelas'); // Hapus kelompok

        // Get data
        $query = Absensi::with(['peserta', 'jadwalSesi.mataKuliah'])
            ->whereDate('waktu_absen', $tanggal);

        // HAPUS FILTER KELOMPOK
        if ($kelas) {
            $query->whereHas('peserta', function($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }

        $absensi = $query->get();
        $totalAbsensi = $absensi->count();
        $totalHadir = $absensi->where('status', 'hadir')->count();
        $totalTerlambat = $absensi->where('status', 'terlambat')->count();

        $data = [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'totalAbsensi' => $totalAbsensi,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'kelas' => $kelas // Hapus kelompok dari data
        ];

        $pdf = PDF::loadView('exports.absensi-pdf', $data);
        return $pdf->download('laporan-absensi-' . $tanggal . '.pdf');
    }

    // Export Peserta to Excel
    public function exportPesertaExcel()
    {
        return Excel::download(new PesertaExport, 'data-peserta-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    // Export Peserta to PDF dengan Barcode
    public function exportPesertaPDF()
    {
        $peserta = Peserta::all();
        
        // Generate QR code base64 untuk PDF
        $pesertaWithQR = $peserta->map(function($item) {
            // Generate QR Code sebagai base64
            try {
                $qrCode = QrCode::format('png')
                    ->size(100)
                    ->margin(1)
                    ->generate($item->barcode_data);
                
                $item->qr_code_base64 = 'data:image/png;base64,' . base64_encode($qrCode);
            } catch (\Exception $e) {
                // Fallback jika QR Code gagal
                $item->qr_code_base64 = null;
            }
            
            return $item;
        });
        
        $data = [
            'peserta' => $pesertaWithQR,
            'totalPeserta' => $peserta->count(),
            'exportDate' => Carbon::now()->format('d F Y')
        ];

        $pdf = PDF::loadView('exports.peserta-pdf', $data)
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('data-peserta-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate simple barcode using GD library (backup method)
     */
    private function generateSimpleBarcode($data)
    {
        if (!extension_loaded('gd')) {
            return null;
        }

        $height = 50;
        $width = 180;
        
        // Create image
        $image = imagecreate($width, $height);
        
        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background
        imagefill($image, 0, 0, $white);
        
        // Add border
        imagerectangle($image, 0, 0, $width-1, $height-1, $black);
        
        // Convert data to simple barcode pattern
        $hash = md5($data);
        $binary = '';
        for ($i = 0; $i < strlen($hash); $i++) {
            $binary .= sprintf('%04b', hexdec($hash[$i]));
        }
        
        // Draw barcode lines
        $x = 10;
        $barHeight = 30;
        for ($i = 0; $i < strlen($binary); $i++) {
            if ($binary[$i] == '1') {
                imageline($image, $x, 10, $x, 10 + $barHeight, $black);
            }
            $x += 2;
        }
        
        // Add text below barcode
        $textWidth = imagefontwidth(2) * strlen($data);
        $textX = ($width - $textWidth) / 2;
        imagestring($image, 2, $textX, $height - 15, $data, $black);
        
        // Capture output
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    // Show export form
    public function showExportForm()
    {
        // HAPUS KELOMPOK LIST
        $kelasList = Peserta::distinct()->whereNotNull('kelas')->pluck('kelas');
        
        return view('exports.form', compact('kelasList')); // Hapus kelompokList
    }

    // Export Template untuk Import
    public function exportTemplate()
    {
        // Buat template Excel sederhana
        $templateData = [
            ['Nama', 'Jabatan', 'Kelas'] // Hapus Kelompok
        ];
        
        $filename = 'template-import-peserta-' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new class($templateData) extends \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function array(): array
            {
                return $this->data;
            }
        }, $filename);
    }
}