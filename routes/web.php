<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MataKuliahController;

// Public routes - Bisa diakses tanpa login
Route::get('/', [AbsensiController::class, 'scannerPublic'])->name('home'); // Default langsung ke scanner public

// Scanner untuk peserta (tanpa login) - PERBAIKI INI
Route::get('/scanner', [AbsensiController::class, 'scannerPublic'])->name('scanner.public'); // Untuk login page
Route::get('/scanner', [AbsensiController::class, 'scannerPublic'])->name('scanner'); // Untuk scanner page
Route::post('/absensi/process', [AbsensiController::class, 'processAbsensi'])->name('absensi.process');

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (harus login)
Route::middleware(['auth:petugas'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin scanner (dengan akses lebih)
    Route::get('/admin/scanner', [AbsensiController::class, 'scanner'])->name('scanner.admin');
    
    // Peserta routes
    Route::resource('peserta', PesertaController::class)->parameters([
        'peserta' => 'peserta'
    ]);
    
    Route::get('/peserta/{peserta}/download-qrcode', [PesertaController::class, 'downloadQrCode'])
         ->name('peserta.download-qrcode');

    // Mata Kuliah routes
    Route::resource('mata-kuliah', MataKuliahController::class);

    // Jadwal routes
    Route::resource('jadwal', JadwalController::class);
    Route::get('/jadwal/aktif', [JadwalController::class, 'getJadwalAktif'])->name('jadwal.getJadwalAktif');

    // Absensi routes
    Route::get('/absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    Route::get('/absensi/filter', [AbsensiController::class, 'filterRiwayat'])->name('absensi.filter');

    // Export routes
    Route::get('/export', [ExportController::class, 'showExportForm'])->name('export.form');
    Route::get('/export/absensi/excel', [ExportController::class, 'exportAbsensiExcel'])->name('export.absensi.excel');
    Route::get('/export/absensi/pdf', [ExportController::class, 'exportAbsensiPDF'])->name('export.absensi.pdf');
    Route::get('/export/peserta/excel', [ExportController::class, 'exportPesertaExcel'])->name('export.peserta.excel');
    Route::get('/export/peserta/pdf', [ExportController::class, 'exportPesertaPDF'])->name('export.peserta.pdf');
    
    // Import routes
    Route::post('/import/peserta', [ImportController::class, 'importPeserta'])->name('import.peserta');
    Route::get('/import/peserta/template', [ImportController::class, 'downloadTemplate'])->name('export.peserta.template');
});