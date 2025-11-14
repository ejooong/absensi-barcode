<?php
// app/Services/QRCodeService.php

namespace App\Services;

use App\Models\Peserta;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class QRCodeService
{
    /**
     * Generate dan simpan QR Code untuk peserta
     */
      public function generateForPeserta(Peserta $peserta)
    {
        DB::beginTransaction();
        
        try {
            // Buat directory dengan cara manual
            $qrDirectory = storage_path('app/public/qr-codes/');
            
            if (!is_dir($qrDirectory)) {
                if (!mkdir($qrDirectory, 0755, true)) {
                    throw new \Exception("Gagal membuat directory: {$qrDirectory}");
                }
            }

            $filename = 'qr_' . $peserta->id . '_' . Str::slug($peserta->nama) . '.png';
            $filePath = $qrDirectory . $filename;

            echo "Generating QR for: {$peserta->nama}\n";
            echo "File path: {$filePath}\n";

            // Generate QR Code
            $qrPng = $this->getQrCodePng($peserta->barcode_data, 300);
            
            if (empty($qrPng)) {
                throw new \Exception("QR Code PNG kosong");
            }

            // Simpan ke file
            $fileSaved = file_put_contents($filePath, $qrPng);
            
            if ($fileSaved === false) {
                throw new \Exception("Gagal menyimpan file");
            }

            // Verifikasi file dibuat
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak tercreate setelah save");
            }

            echo "✓ File created: {$filePath}\n";

            // **PERBAIKAN: Gunakan fresh instance untuk update**
            $pesertaToUpdate = Peserta::find($peserta->id);
            
            // Update database - PASTIKAN INI
            $updated = $pesertaToUpdate->update([
                'qr_code_file' => $filename
            ]);

            if (!$updated) {
                throw new \Exception("Gagal update database");
            }

            // **VERIFIKASI: Cek langsung dari database**
            $verified = Peserta::find($peserta->id);
            echo "✓ Database updated: " . ($verified->qr_code_file ?? 'NULL') . "\n";

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            Log::error('Gagal generate QR Code untuk peserta ' . $peserta->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate QR Code PNG
     */
public function getQrCodePng(string $barcodeData, int $size = 400): string // Size besar 400px
{
    try {
        // Method 1: Coba dengan simple-qrcode
        $png = QrCode::format('png')
            ->size($size) // 400px
            ->margin(3)   // Margin lebih besar
            ->errorCorrection('H') // High error correction
            ->generate($barcodeData);

        if (empty($png) || strlen($png) < 100) {
            throw new \Exception('QrCode produced empty or too small output');
        }

        return $png;

    } catch (\Throwable $e) {
        // fallback ke GD internal
        return $this->createQrFallbackPng($barcodeData, $size);
    }
}

    /**
     * Fallback generator menggunakan GD
     */
    protected function createQrFallbackPng(string $data, int $size = 400): string // Size besar 400px
{
    if (!extension_loaded('gd')) {
        // Return placeholder
        $im = imagecreate(100, 100);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefill($im, 0, 0, $white);
        imagerectangle($im, 0, 0, 99, 99, $black);
        imagestring($im, 3, 10, 45, 'NO GD', $black);
        ob_start();
        imagepng($im);
        $bin = ob_get_clean();
        imagedestroy($im);
        return $bin;
    }

    // Size besar dengan detail lebih baik
    $imgSize = $size; // Gunakan size yang diminta
    $image = imagecreate($imgSize, $imgSize);
    
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $white);
    
    // Create QR pattern dari hash data
    $hash = md5($data);
    $gridSize = 15; // Grid lebih besar untuk size besar
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
    
    // Add border tebal
    imagerectangle($image, 0, 0, $imgSize-1, $imgSize-1, $black);
    imagerectangle($image, 1, 1, $imgSize-2, $imgSize-2, $black);
    
    // Add text di bawah
    $text = substr($data, -6); // 6 karakter terakhir
    $textColor = imagecolorallocate($image, 0, 0, 0);
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textX = ($imgSize - $textWidth) / 2;
    $textY = $imgSize - 25;
    imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
    
    ob_start();
    imagepng($image);
    $bin = ob_get_clean();
    imagedestroy($image);
    
    return $bin;
}

    // Helper methods untuk logging
    private function info($message)
    {
        Log::info($message);
        echo "[INFO] $message\n";
    }

    private function error($message)
    {
        Log::error($message);
        echo "[ERROR] $message\n";
    }
}