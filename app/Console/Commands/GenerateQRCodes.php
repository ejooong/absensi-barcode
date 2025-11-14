<?php
// app/Console/Commands/GenerateQRCodes.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use App\Services\QRCodeService;

class GenerateQRCodes extends Command
{
    protected $signature = 'qr:generate {--id=}';
    protected $description = 'Generate QR Code untuk peserta yang belum memiliki file QR';

    public function handle()
    {
        $qrService = new QRCodeService();
        
        $query = Peserta::whereNull('qr_code_file');
        
        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        }
        
        $peserta = $query->get();
        
        if ($peserta->isEmpty()) {
            $this->info('Tidak ada peserta yang perlu digenerate QR Code.');
            return;
        }
        
        $this->info("Men-generate QR Code untuk {$peserta->count()} peserta...");
        
        $successCount = 0;
        foreach ($peserta as $p) {
            $this->info("\n" . str_repeat('=', 50));
            $this->info("Processing: ID {$p->id} - {$p->nama}");
            $this->info("Barcode: {$p->barcode_data}");
            
            try {
                if ($qrService->generateForPeserta($p)) {
                    $successCount++;
                    $this->info("✓ SUCCESS for ID: {$p->id}");
                    
                    // Verify update
                    $updated = Peserta::find($p->id);
                    $this->info("QR File in DB: " . ($updated->qr_code_file ?? 'NULL'));
                    
                    // Verify file exists
                    $filePath = storage_path('app/public/qr-codes/' . $updated->qr_code_file);
                    if (file_exists($filePath)) {
                        $this->info("✓ File exists: " . $filePath);
                    } else {
                        $this->error("✗ File missing: " . $filePath);
                    }
                } else {
                    $this->error("✗ FAILED for ID: {$p->id}");
                }
            } catch (\Exception $e) {
                $this->error("ERROR for ID {$p->id}: " . $e->getMessage());
            }
            
            $this->info(str_repeat('=', 50));
        }
        
        $this->info("\n=== FINAL RESULT ===");
        $this->info("Berhasil generate: {$successCount} QR Code");
        
        $nullCount = Peserta::whereNull('qr_code_file')->count();
        $this->info("Sisa tanpa QR Code: {$nullCount}");
        
        // Show all files in directory
        $qrDir = storage_path('app/public/qr-codes/');
        if (is_dir($qrDir)) {
            $files = scandir($qrDir);
            $this->info("Files in qr-codes directory: " . implode(', ', array_filter($files, function($f) {
                return $f !== '.' && $f !== '..';
            })));
        } else {
            $this->error("Directory tidak ada: {$qrDir}");
        }
    }
}