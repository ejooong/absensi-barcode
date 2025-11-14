<?php
// app/Console/Commands/GenerateHighResQRCodes.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Storage;

class GenerateHighResQRCodes extends Command
{
    protected $signature = 'qr:highres';
    protected $description = 'Regenerate semua QR Code dengan HIGH RESOLUTION 800px';

    public function handle()
    {
        $controller = new PesertaController();
        $peserta = Peserta::all();

        $this->info("Regenerating {$peserta->count()} QR Codes dengan HIGH RESOLUTION 800px...");

        // Pastikan directory ada
        $qrDirectory = storage_path('app/public/qr-codes/');
        if (!is_dir($qrDirectory)) {
            Storage::makeDirectory('public/qr-codes');
            $this->info("Created directory: {$qrDirectory}");
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($peserta as $p) {
            $this->info("\nProcessing: {$p->nama} (ID: {$p->id})");
            
            try {
                // Delete old file jika ada
                if ($p->qr_code_file) {
                    $oldPath = storage_path('app/public/qr-codes/' . $p->qr_code_file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                        $this->info("  Deleted old file: {$p->qr_code_file}");
                    }
                }
                
                // Regenerate dengan HIGH RES
                $this->info("  Generating new QR Code...");
                $result = $controller->generateAndSaveQRCode($p);
                
                if ($result) {
                    // Verify
                    $p->refresh();
                    
                    if (!$p->qr_code_file) {
                        $this->error("  ✗ QR Code file name is NULL in database");
                        $failCount++;
                        continue;
                    }
                    
                    $filePath = storage_path('app/public/qr-codes/' . $p->qr_code_file);
                    
                    if (file_exists($filePath)) {
                        $size = filesize($filePath);
                        $this->info("  ✓ File created: {$p->qr_code_file}");
                        $this->info("  ✓ File size: " . round($size/1024, 2) . " KB");
                        
                        // Check dimensions dengan error handling
                        if ($size > 0) {
                            // Perbaikan: Gunakan full path yang benar
                            $fullPath = storage_path('app/public/qr-codes/' . $p->qr_code_file);
                            
                            if (file_exists($fullPath)) {
                                $imageInfo = @getimagesize($fullPath);
                                if ($imageInfo !== false) {
                                    list($width, $height) = $imageInfo;
                                    $this->info("  ✓ Dimensions: {$width}x{$height} pixels");
                                } else {
                                    $this->warn("  ⚠ Could not read image dimensions (file may be corrupt)");
                                }
                            } else {
                                $this->error("  ✗ File not found at: {$fullPath}");
                                $failCount++;
                                continue;
                            }
                        } else {
                            $this->error("  ✗ File is empty (0 bytes)");
                            $failCount++;
                            continue;
                        }
                        
                        $successCount++;
                    } else {
                        $this->error("  ✗ File not created after generation");
                        $this->error("  Expected path: {$filePath}");
                        $failCount++;
                    }
                } else {
                    $this->error("  ✗ QR generation failed");
                    $failCount++;
                }
                
            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
                $failCount++;
            }
        }

        $this->info("\n" . str_repeat("=", 50));
        $this->info("FINAL RESULT:");
        $this->info("Success: {$successCount}");
        $this->info("Failed: {$failCount}");
        $this->info("Total: " . ($successCount + $failCount));
        
        if ($failCount > 0) {
            $this->error("Some QR Codes failed to generate. Check the logs above.");
        } else {
            $this->info("All QR Code HIGH RES 800px berhasil di-generate!");
        }
    }
}