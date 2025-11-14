@extends('layouts.admin')

@section('title', 'Detail Mata Kuliah')
@section('subtitle', 'Detail informasi mata kuliah')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Info -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $mataKuliah->nama_materi }}</h2>
                <p class="text-gray-600 mt-1">Kode: <span class="font-mono text-blue-600">{{ $mataKuliah->kode_materi }}</span></p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('mata-kuliah.edit', $mataKuliah->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('jadwal.create') }}?mata_kuliah_id={{ $mataKuliah->id }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            @php
                $now = now();
                $jadwalSesi = $mataKuliah->jadwalSesi;
                $sedangBerlangsung = $jadwalSesi->filter(function($jadwal) use ($now) {
                    return $now->between($jadwal->waktu_mulai, $jadwal->waktu_akhir);
                })->count();
                $akanDatang = $jadwalSesi->filter(function($jadwal) use ($now) {
                    return $now->lt($jadwal->waktu_mulai);
                })->count();
                $selesai = $jadwalSesi->filter(function($jadwal) use ($now) {
                    return $now->gt($jadwal->waktu_akhir);
                })->count();
            @endphp
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <div class="text-blue-600 font-semibold text-xl">{{ $jadwalSesi->count() }}</div>
                <div class="text-blue-800 text-sm">Total Sesi</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="text-green-600 font-semibold text-xl">{{ $sedangBerlangsung }}</div>
                <div class="text-green-800 text-sm">Sedang Berlangsung</div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <div class="text-yellow-600 font-semibold text-xl">{{ $akanDatang }}</div>
                <div class="text-yellow-800 text-sm">Akan Datang</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                <div class="text-gray-600 font-semibold text-xl">{{ $selesai }}</div>
                <div class="text-gray-800 text-sm">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Jadwal Sesi -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Sesi</h3>
        
        @if($mataKuliah->jadwalSesi->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sesi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($mataKuliah->jadwalSesi->sortBy('waktu_mulai') as $jadwal)
                        @php
                            $now = now();
                            if ($now->between($jadwal->waktu_mulai, $jadwal->waktu_akhir)) {
                                $status = 'aktif';
                            } elseif ($now->lt($jadwal->waktu_mulai)) {
                                $status = 'akan_datang';
                            } else {
                                $status = 'selesai';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                Sesi {{ $jadwal->sesi_ke }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">
                                {{ $jadwal->materi }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $jadwal->waktu_mulai->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($status === 'aktif')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Berlangsung
                                    </span>
                                @elseif($status === 'akan_datang')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                <p>Belum ada jadwal untuk mata kuliah ini</p>
                <a href="{{ route('jadwal.create') }}?mata_kuliah_id={{ $mataKuliah->id }}" 
                   class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    Buat jadwal pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection