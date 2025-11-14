<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Absensi;
use App\Models\JadwalSesi;
use App\Models\MataKuliah;
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
        
        // Hitung jadwal aktif (sedang berlangsung)
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->count();
            
        // Total mata kuliah
        $totalMataKuliah = MataKuliah::count();
        
        // Absensi terbaru hari ini
        $recentAbsensi = Absensi::with(['peserta', 'jadwalSesi.mataKuliah'])
            ->whereDate('waktu_absen', $today)
            ->orderBy('waktu_absen', 'desc')
            ->limit(5)
            ->get();
            
        // Jadwal hari ini
        $jadwalHariIni = JadwalSesi::with('mataKuliah')
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
            'jadwalAktif',
            'totalMataKuliah',
            'recentAbsensi',
            'jadwalHariIni',
            'absensiBulanIni'
        ));
    }
}