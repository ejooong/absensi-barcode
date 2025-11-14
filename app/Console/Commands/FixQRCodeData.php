<?php
// app/Console/Commands/FixQRCodeData.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use Illuminate\Support\Facades\DB;

class FixQRCodeData extends Command
{
    protected $signature = 'qr:fix';
    protected $description = 'Fix QR Code data untuk file yang sudah ada';

    public function handle()
    {
        $qrDirectory = storage_path('app/public/qr-codes/');
        
        if (!is_dir($qrDirectory)) {
            $this->error("Directory qr-codes tidak ada!");
            return;
        }

        // Get all QR files
        $files = array_filter(scandir($qrDirectory), function($file) {
            return $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'png';
        });

        $this->info("Found " . count($files) . " QR files");

        $updatedCount = 0;
        
        foreach ($files as $filename) {
            // Extract ID from filename: qr_11_ezza-eka-pramana.png
            if (preg_match('/^qr_(\d+)_/', $filename, $matches)) {
                $pesertaId = $matches[1];
                
                $peserta = Peserta::find($pesertaId);
                if ($peserta) {
                    // Update database
                    $peserta->qr_code_file = $filename;
                    $peserta->save();
                    
                    $this->info("✓ Updated peserta {$pesertaId}: {$filename}");
                    $updatedCount++;
                } else {
                    $this->error("✗ Peserta ID {$pesertaId} tidak ditemukan");
                }
            }
        }

        $this->info("\n=== RESULT ===");
        $this->info("Berhasil update: {$updatedCount} peserta");
        
        // Final check
        $totalPeserta = Peserta::count();
        $withQR = Peserta::whereNotNull('qr_code_file')->count();
        $withoutQR = Peserta::whereNull('qr_code_file')->count();
        
        $this->info("Total peserta: {$totalPeserta}");
        $this->info("Dengan QR Code: {$withQR}");
        $this->info("Tanpa QR Code: {$withoutQR}");
    }
}