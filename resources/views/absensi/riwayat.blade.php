<!-- resources/views/absensi/riwayat.blade.php -->
@extends('layouts.admin')

@section('title', 'Riwayat Absensi')
@section('subtitle', 'Data history kehadiran peserta')

@section('content')
<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Riwayat Absensi</h2>
    </div>
    
    <!-- Filter Form -->
    <form method="GET" action="{{ route('absensi.filter') }}" class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
        @csrf
        <div class="w-full sm:w-auto">
            <input type="date" 
                   name="tanggal" 
                   value="{{ request('tanggal', date('Y-m-d')) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="w-full sm:w-auto">
            <input type="text" 
                   name="nama" 
                   value="{{ request('nama') }}"
                   placeholder="Cari nama..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="w-full sm:w-auto">
            <select name="kelas" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex space-x-2 w-full sm:w-auto">
            <button type="submit" 
                    class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('absensi.riwayat') }}" 
               class="flex-1 sm:flex-none bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                <i class="fas fa-refresh mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $totalHadir = $absensi->where('status', 'hadir')->count();
        $totalTerlambat = $absensi->where('status', 'terlambat')->count();
        $totalAbsensi = $absensi->count();
    @endphp
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="text-blue-600 font-semibold text-lg">{{ $totalAbsensi }}</div>
        <div class="text-blue-800 text-sm">Total Absensi</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="text-green-600 font-semibold text-lg">{{ $totalHadir }}</div>
        <div class="text-green-800 text-sm">Hadir Tepat Waktu</div>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="text-yellow-600 font-semibold text-lg">{{ $totalTerlambat }}</div>
        <div class="text-yellow-800 text-sm">Terlambat</div>
    </div>
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="text-purple-600 font-semibold text-lg">{{ $absensi->unique('peserta_id')->count() }}</div>
        <div class="text-purple-800 text-sm">Peserta Unik</div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Absen</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($absensi as $absen)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $absen->peserta->nama }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $absen->peserta->jabatan ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $absen->kegiatan->program->nama_materi ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">Sesi {{ $absen->kegiatan->sesi_ke ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $absen->kegiatan->materi ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $absen->waktu_absen->format('H:i:s') }}</div>
                        <div class="text-xs text-gray-500">{{ $absen->waktu_absen->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $absen->status == 'hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($absen->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-clipboard-check text-3xl mb-3 text-gray-400"></i>
                        <p class="text-lg">Belum ada data absensi</p>
                        <p class="text-sm mt-1">Data akan muncul setelah peserta melakukan absensi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($absensi->hasPages())
<div class="mt-6">
    {{ $absensi->links() }}
</div>
@endif
@endsection