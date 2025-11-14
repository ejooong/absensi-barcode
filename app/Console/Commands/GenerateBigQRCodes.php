<?php
// app/Console/Commands/GenerateBigQRCodes.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use App\Http\Controllers\PesertaController;

class GenerateBigQRCodes extends Command
{
    protected $signature = 'qr:big';
    protected $description = 'Regenerate semua QR Code dengan size besar';

    public function handle()
    {
        $controller = new PesertaController();
        $peserta = Peserta::all();

        $this->info("Regenerating {$peserta->count()} QR Codes dengan size besar...");

        foreach ($peserta as $p) {
            $this->info("Processing: {$p->nama}");
            
            // Delete old file
            if ($p->qr_code_file) {
                $oldPath = storage_path('app/public/qr-codes/' . $p->qr_code_file);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Regenerate dengan size besar
            $controller->generateAndSaveQRCode($p);
            
            // Verify
            $p->refresh();
            $filePath = storage_path('app/public/qr-codes/' . $p->qr_code_file);
            $size = file_exists($filePath) ? filesize($filePath) : 0;
            $this->info("✓ {$p->nama} - File size: {$size} bytes");
        }

        $this->info("Semua QR Code berhasil di-regenerate dengan size besar!");
    }
}