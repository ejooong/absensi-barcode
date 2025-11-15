@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold">{{ $kegiatan->program->nama_materi}}</h1>
                    <p class="text-blue-100 text-lg">Sesi {{ $kegiatan->sesi_ke }} - {{ $kegiatan->materi }}</p>
                    <div class="flex items-center mt-2 space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $kegiatan->waktu_mulai->format('H:i') }} - {{ $kegiatan->waktu_akhir->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $kegiatan->waktu_mulai->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $now = now();
                        if ($now->between($kegiatan->waktu_mulai, $kegiatan->waktu_akhir)) {
                            $status = 'Berlangsung';
                            $color = 'bg-green-500';
                        } elseif ($now->lt($kegiatan->waktu_mulai)) {
                            $status = 'Akan Datang';
                            $color = 'bg-yellow-500';
                        } else {
                            $status = 'Selesai';
                            $color = 'bg-gray-500';
                        }
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informasi Kegiatan -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kegiatan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Program:</span>
                                <span class="font-semibold">{{ $kegiatan->program->nama_materi }}</span>
                            </div>

                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Sesi:</span>
                                <span class="font-semibold">Sesi {{ $kegiatan->sesi_ke }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Materi:</span>
                                <span class="text-right">{{ $kegiatan->materi }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Waktu:</span>
                                <span>{{ $kegiatan->waktu_mulai->format('H:i') }} - {{ $kegiatan->waktu_akhir->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Tanggal:</span>
                                <span>{{ $kegiatan->waktu_mulai->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Dibuat Oleh:</span>
                                <span>{{ $kegiatan->petugas->username }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Dibuat Pada:</span>
                                <span>{{ $kegiatan->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Kehadiran</h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <div class="text-blue-600 font-bold text-2xl">{{ $kegiatan->absensi->count() }}</div>
                            <div class="text-blue-800 font-medium">Total Kehadiran</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-green-600 font-bold text-2xl">{{ $kegiatan->absensi->where('status', 'hadir')->count() }}</div>
                            <div class="text-green-800 font-medium">Hadir Tepat Waktu</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <div class="text-yellow-600 font-bold text-2xl">{{ $kegiatan->absensi->where('status', 'terlambat')->count() }}</div>
                            <div class="text-yellow-800 font-medium">Terlambat</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Absensi -->
            @if($kegiatan->absensi->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kehadiran</h3>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Kehadiran</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($kegiatan->absensi as $absen)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $absen->peserta->nama }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $absen->peserta->jabatan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $absen->peserta->kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $absen->waktu_absen->format('H:i:s') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $absen->status == 'hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($absen->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="mt-8 text-center py-8 text-gray-500">
                <i class="fas fa-clipboard-list text-3xl mb-2"></i>
                <p>Belum ada absensi untuk sesi ini</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('kegiatan.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <div class="flex space-x-2">
                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @if($kegiatan->absensi->count() === 0)
                <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                            onclick="return confirm('Hapus kegiatan ini?')">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection