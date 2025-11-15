<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Absensi;
use App\Models\Kegiatan; // Ganti dari JadwalSesi
use App\Models\Program; // Ganti dari MataKuliah
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Hitung total peserta
        $totalPeserta = Peserta::count();
        
        // Hitung absensi hari ini
        $absensiHariIni = Absensi::whereDate('waktu_absen', $today)->count();
        
        // Hitung kegiatan aktif (sedang berlangsung)
        $kegiatanAktif = Kegiatan::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->count();
            
        // Total program
        $totalProgram = Program::count();
        
        // Absensi terbaru hari ini
        $recentAbsensi = Absensi::with(['peserta', 'kegiatan.program']) // Ganti dari jadwalSesi.mataKuliah
            ->whereDate('waktu_absen', $today)
            ->orderBy('waktu_absen', 'desc')
            ->limit(5)
            ->get();
            
        // Kegiatan hari ini
        $kegiatanHariIni = Kegiatan::with('program') // Ganti dari mataKuliah
            ->whereDate('waktu_mulai', $today)
            ->orderBy('waktu_mulai')
            ->get();
            
        // Absensi bulan ini
        $absensiBulanIni = Absensi::whereMonth('waktu_absen', $now->month)
            ->whereYear('waktu_absen', $now->year)
            ->count();

        return view('dashboard.index', compact(
            'totalPeserta',
            'absensiHariIni', 
            'kegiatanAktif', // Ganti dari jadwalAktif
            'totalProgram', // Ganti dari totalMataKuliah
            'recentAbsensi',
            'kegiatanHariIni', // Ganti dari jadwalHariIni
            'absensiBulanIni'
        ));
    }
}