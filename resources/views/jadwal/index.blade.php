<!-- resources/views/jadwal/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manajemen Jadwal')
@section('subtitle', 'Kelola jadwal sesi perkuliahan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Daftar Jadwal Sesi</h2>
    </div>
    <a href="{{ route('jadwal.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i>Tambah Jadwal
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    @php
        $now = now();
        $totalJadwal = $jadwal->total();
        $jadwalAktif = $jadwal->where('waktu_mulai', '<=', $now)->where('waktu_akhir', '>=', $now)->count();
        $jadwalAkanDatang = $jadwal->where('waktu_mulai', '>', $now)->count();
        $jadwalSelesai = $jadwal->where('waktu_akhir', '<', $now)->count();
    @endphp
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="text-blue-600 font-semibold text-xl">{{ $totalJadwal }}</div>
        <div class="text-blue-800 text-sm">Total Jadwal</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="text-green-600 font-semibold text-xl">{{ $jadwalAktif }}</div>
        <div class="text-green-800 text-sm">Sedang Berlangsung</div>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="text-yellow-600 font-semibold text-xl">{{ $jadwalAkanDatang }}</div>
        <div class="text-yellow-800 text-sm">Akan Datang</div>
    </div>
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="text-gray-600 font-semibold text-xl">{{ $jadwalSelesai }}</div>
        <div class="text-gray-800 text-sm">Selesai</div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sesi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absensi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jadwal as $j)
                @php
                    $now = now();
                    $status = '';
                    if ($now->between($j->waktu_mulai, $j->waktu_akhir)) {
                        $status = 'aktif';
                    } elseif ($now->lt($j->waktu_mulai)) {
                        $status = 'akan_datang';
                    } else {
                        $status = 'selesai';
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $j->mataKuliah->nama_materi }}</div>
                        <div class="text-sm text-gray-500">{{ $j->mataKuliah->kode_materi }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Sesi {{ $j->sesi_ke }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $j->materi }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $j->waktu_mulai->format('H:i') }} - {{ $j->waktu_akhir->format('H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ $j->waktu_mulai->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($status === 'aktif')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-play mr-1"></i>Berlangsung
                            </span>
                        @elseif($status === 'akan_datang')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Akan Datang
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-check mr-1"></i>Selesai
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $j->absensi_count ?? 0 }} Peserta</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('jadwal.show', $j->id) }}" 
                               class="text-blue-600 hover:text-blue-900"
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('jadwal.edit', $j->id) }}" 
                               class="text-green-600 hover:text-green-900"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Hapus jadwal ini?')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-2xl mb-2"></i>
                        <p>Belum ada jadwal</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($jadwal->hasPages())
<div class="mt-6">
    {{ $jadwal->links() }}
</div>
@endif
@endsection