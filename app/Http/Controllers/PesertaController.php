<?php
// app/Http/Controllers/PesertaController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    public function index()
    {
        $peserta = Peserta::latest()->paginate(10);
        return view('peserta.index', compact('peserta'));
    }

    public function create()
    {
        return view('peserta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
          
            'kelas' => 'nullable|string|max:50'
        ]);

        // Generate unique barcode data
        $barcodeData = 'ABN-' . time() . '-' . rand(1000, 9999);

        // Create peserta dulu
        $peserta = Peserta::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            
            'kelas' => $request->kelas,
            'barcode_data' => $barcodeData
        ]);

        // Generate dan simpan QR Code sebagai file HIGH RES
        $this->generateAndSaveQRCode($peserta);

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan!');
    }

    private function generateAndSaveQRCode(Peserta $peserta)
{
    try {
        $qrDirectory = 'public/qr-codes/';
        if (!Storage::exists($qrDirectory)) {
            Storage::makeDirectory($qrDirectory);
        }

        $filename = 'qr_' . $peserta->id . '_' . Str::slug($peserta->nama) . '.png';
        $filePath = storage_path('app/' . $qrDirectory . $filename);

        // Cek apakah Imagick tersedia
        if ($this->isImagickAvailable()) {
            $qrPng = $this->getQrCodePng($peserta->barcode_data, 800);
        } else {
            // Fallback ke GD
            $qrPng = $this->generateQRWithSimpleQRCoder($peserta->barcode_data, 800);
        }

        file_put_contents($filePath, $qrPng);
        $peserta->update(['qr_code_file' => $filename]);

        return true;

    } catch (\Exception $e) {
        \Log::error('QR Generation failed: ' . $e->getMessage());
        
        // Final fallback - gunakan simple QR code saja
        try {
            $qrPng = $this->generateQRWithSimpleQRCoder($peserta->barcode_data, 800);
            file_put_contents($filePath, $qrPng);
            $peserta->update(['qr_code_file' => $filename]);
            return true;
        } catch (\Exception $e2) {
            \Log::error('Fallback juga gagal: ' . $e2->getMessage());
            return false;
        }
    }
}

private function isImagickAvailable()
{
    return extension_loaded('imagick') && class_exists('Imagick');
}

private function generateQRWithSimpleQRCoder($data, $size = 800)
{
    // Gunakan simple-qrcode package yang sudah ada
    return QrCode::format('png')
        ->size($size)
        ->margin(2)
        ->errorCorrection('H')
        ->generate($data);
}

    public function show(Peserta $peserta)
    {
        return view('peserta.show', compact('peserta'));
    }

    public function edit(Peserta $peserta)
    {
        return view('peserta.edit', compact('peserta'));
    }

    public function update(Request $request, Peserta $peserta)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
           
            'kelas' => 'nullable|string|max:50'
        ]);

        $peserta->update($request->all());

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil diupdate!');
    }

    public function destroy(Peserta $peserta)
    {
        $peserta->delete();
        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil dihapus!');
    }

    /**
     * (Optional) Generate QR Code untuk ditampilkan di view (SVG)
     */
    public function generateQrCode($barcodeData)
    {
        return QrCode::size(200)->generate($barcodeData);
    }

    /**
     * Helper: dapatkan PNG binary QR dari barcode data - HIGH RES
     */
    public function getQrCodePng(string $barcodeData, int $size = 800): string // DEFAULT 800px HIGH RES
    {
        try {
            // coba generate via simple-qrcode dengan HIGH RES
            $png = QrCode::format('png')
                ->size($size) // 800px
                ->margin(2)   // Margin lebih besar
                ->errorCorrection('H') // High error correction
                ->generate($barcodeData);

            if (empty($png) || strlen($png) < 100) {
                throw new \Exception('QrCode produced empty output');
            }

            return $png;
        } catch (\Throwable $e) {
            // fallback ke GD internal HIGH RES
            return $this->createQrFallbackPng($barcodeData, $size);
        }
    }

    /**
     * Download QR Code as PNG attachment HIGH RES
     */
    public function downloadQrCode(Peserta $peserta)
    {
        $png = $this->getQrCodePng($peserta->barcode_data, 800); // 800px HIGH RES

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . preg_replace('/[^A-Za-z0-9_-]/','_', $peserta->nama) . '.png"');
    }

    /**
     * Fallback generator: buat PNG binary menggunakan GD - HIGH RES
     */
    protected function createQrFallbackPng(string $data, int $size = 800): string // 800px HIGH RES
    {
        if (!extension_loaded('gd')) {
            // Return placeholder HIGH RES
            $im = imagecreate(300, 300);
            $white = imagecolorallocate($im, 255, 255, 255);
            $black = imagecolorallocate($im, 0, 0, 0);
            imagefill($im, 0, 0, $white);
            imagerectangle($im, 0, 0, 299, 299, $black);
            imagestring($im, 5, 100, 140, 'NO GD', $black);
            ob_start();
            imagepng($im);
            $bin = ob_get_clean();
            imagedestroy($im);
            return $bin;
        }

        // HIGH RESOLUTION - 800px dengan detail lebih baik
        $imgSize = $size;
        $image = imagecreatetruecolor($imgSize, $imgSize);
        
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        // Create detailed QR pattern untuk high res
        $hash = md5($data);
        $gridSize = 25; // Grid lebih besar untuk high res
        $cols = floor($imgSize / $gridSize);
        
        for ($i = 0; $i < min(strlen($hash), $cols * $cols); $i++) {
            $bit = hexdec($hash[$i]) > 7;
            if ($bit) {
                $col = $i % $cols;
                $row = floor($i / $cols);
                
                $x1 = $col * $gridSize;
                $y1 = $row * $gridSize;
                $x2 = $x1 + $gridSize - 1;
                $y2 = $y1 + $gridSize - 1;
                
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $black);
            }
        }
        
        // Add thick border untuk high res
        imagerectangle($image, 0, 0, $imgSize-1, $imgSize-1, $black);
        imagerectangle($image, 1, 1, $imgSize-2, $imgSize-2, $black);
        imagerectangle($image, 2, 2, $imgSize-3, $imgSize-3, $black);
        
        // Add text dengan font lebih besar
        $text = substr($data, -8);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textX = ($imgSize - $textWidth) / 2;
        $textY = $imgSize - 50;
        imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
        
        ob_start();
        imagepng($image);
        $bin = ob_get_clean();
        imagedestroy($image);
        
        return $bin;
    }
}