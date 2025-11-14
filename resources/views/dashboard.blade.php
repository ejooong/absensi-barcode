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

    <!-- Rest of your dashboard content remains the same -->
    <!-- ... -->
</div>

<!-- Welcome Modal -->
@if(auth()->guard('petugas')->user()->created_at->diffInDays(now()) < 1)
<div id="welcomeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Selamat Datang!</h3>
            <p class="text-gray-600 mb-6">Sistem Absensi Barcode siap digunakan. Mulai dengan menambahkan peserta atau membuat jadwal.</p>
            <div class="flex space-x-3">
                <button onclick="closeWelcomeModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg font-medium transition-colors duration-200">
                    Tutup
                </button>
                <a href="{{ route('peserta.create') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                    Mulai
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function closeWelcomeModal() {
        const modal = document.getElementById('welcomeModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Auto close welcome modal after 10 seconds
    setTimeout(() => {
        closeWelcomeModal();
    }, 10000);
</script>
@endsection