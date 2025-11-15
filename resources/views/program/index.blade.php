<!-- resources/views/program/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Daftar Program</h2>
    </div>
    <a href="{{ route('program.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i>Tambah Program
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @php
        $totalProgram = $program->total();
        // Perbaikan: Gunakan count() biasa untuk Collection
        $totalWithKegiatan = $program->filter(function($p) {
            return $p->kegiatan->count() > 0;
        })->count();
        $totalWithoutKegiatan = $totalProgram - $totalWithKegiatan;
    @endphp
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="text-blue-600 font-semibold text-xl">{{ $totalProgram }}</div>
        <div class="text-blue-800 text-sm">Total Program</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="text-green-600 font-semibold text-xl">{{ $totalWithKegiatan }}</div>
        <div class="text-green-800 text-sm">Program Sedang Berjalan</div>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="text-yellow-600 font-semibold text-xl">{{ $totalWithoutKegiatan }}</div>
        <div class="text-yellow-800 text-sm">Program Belum Berjalan</div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($program as $p)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $p->nama_materi }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $p->kegiatan->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $p->kegiatan->count() }} kegiatan
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $p->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $p->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('program.show', $p->id) }}" 
                               class="text-blue-600 hover:text-blue-900"
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('program.edit', $p->id) }}" 
                               class="text-green-600 hover:text-green-900"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('program.destroy', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Hapus program {{ $p->nama_materi }}?')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-book text-2xl mb-2"></i>
                        <p>Belum ada program</p>
                        <a href="{{ route('program.create') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                            Tambah program pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($program->hasPages())
<div class="mt-6">
    {{ $program->links() }}
</div>
@endif
@endsection