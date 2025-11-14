<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview sistem absensi barcode')

@section('content')
@php
    // Fallback values jika variabel tidak terdefinisi
    $totalPeserta = $totalPeserta ?? 0;
    $absensiHariIni = $absensiHariIni ?? 0;
    $jadwalAktif = $jadwalAktif ?? 0;
    $totalMataKuliah = $totalMataKuliah ?? 0;
    $recentAbsensi = $recentAbsensi ?? collect();
    $jadwalHariIni = $jadwalHariIni ?? collect();
    $absensiBulanIni = $absensiBulanIni ?? 0;
@endphp

<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Peserta -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-sync-alt mr-1"></i>
                    <span>Updated recently</span>
                </div>
            </div>
        </div>

        <!-- Absensi Hari Ini -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Absensi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $absensiHariIni }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    <span>{{ date('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Jadwal Aktif -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Jadwal Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $jadwalAktif }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-play-circle mr-1"></i>
                    <span>Sesi berlangsung</span>
                </div>
            </div>
        </div>

        <!-- Mata Kuliah -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Mata Kuliah</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMataKuliah }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-book text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    <span>Total mata kuliah</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Scanner Absensi</h3>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                <i class="fas fa-circle mr-1"></i>Ready
            </span>
        </div>

        <div class="text-center">
            <div class="mb-6">
                <i class="fas fa-qrcode text-6xl text-blue-500 mb-4"></i>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Scan QR Code untuk Absensi</h4>
                <p class="text-gray-600">Gunakan scanner untuk memindai QR Code peserta</p>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('scanner') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-camera mr-2"></i>
                    Buka Scanner
                </a>
                
                <a href="{{ route('absensi.riwayat') }}" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Absensi -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
            <span class="text-sm text-gray-500">{{ $absensiHariIni }} hari ini</span>
        </div>
        <div class="space-y-3">
            @forelse($recentAbsensi as $absensi)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $absensi->peserta->nama ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $absensi->waktu_absen->format('H:i:s') }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                    {{ $absensi->status }}
                </span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-history text-3xl mb-3"></i>
                <p>Belum ada absensi hari ini</p>
            </div>
            @endforelse
        </div>
    </div>
</div>


@endsection