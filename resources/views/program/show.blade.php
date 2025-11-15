@extends('layouts.admin')



@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Info -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $program->nama_materi }}</h1>
                <p class="text-gray-600 mt-2">Program aktif dengan {{ $program->kegiatan->count() }} kegiatan</p>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('program.edit', $program->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('kegiatan.create') }}?program_id={{ $program->id }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            @php
                $now = now();
                $kegiatan = $program->kegiatan;
                $sedangBerlangsung = $kegiatan->filter(function($kegiatan) use ($now) {
                    return $now->between($kegiatan->waktu_mulai, $kegiatan->waktu_akhir);
                })->count();
                $akanDatang = $kegiatan->filter(function($kegiatan) use ($now) {
                    return $now->lt($kegiatan->waktu_mulai);
                })->count();
                $selesai = $kegiatan->filter(function($kegiatan) use ($now) {
                    return $now->gt($kegiatan->waktu_akhir);
                })->count();
            @endphp
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <div class="text-blue-600 font-semibold text-xl">{{ $kegiatan->count() }}</div>
                <div class="text-blue-800 text-sm">Kegiatan</div>
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

    <!-- Kegiatan -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kegiatan</h3>
        
        @if($program->kegiatan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sesi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($program->kegiatan->sortBy('waktu_mulai') as $kegiatan)
                        @php
                            $now = now();
                            if ($now->between($kegiatan->waktu_mulai, $kegiatan->waktu_akhir)) {
                                $status = 'aktif';
                            } elseif ($now->lt($kegiatan->waktu_mulai)) {
                                $status = 'akan_datang';
                            } else {
                                $status = 'selesai';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                Sesi {{ $kegiatan->sesi_ke }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">
                                {{ $kegiatan->materi }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $kegiatan->waktu_mulai->format('d/m/Y H:i') }}
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
                <p>Belum ada kegiatan untuk program ini</p>
                <a href="{{ route('kegiatan.create') }}?program_id={{ $program->id }}" 
                   class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    Buat kegiatan pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection