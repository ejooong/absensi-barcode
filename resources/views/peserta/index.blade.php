<!-- resources/views/peserta/index.blade.php -->
@extends('layouts.admin')


@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Daftar Peserta</h2>
    </div>
    <a href="{{ route('peserta.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
        <i class="fas fa-user-plus mr-2"></i>Tambah Peserta
    </a>
</div>


<!-- Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                    
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peserta as $p)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $p->nama }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $p->jabatan ?? '-' }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $p->kelas ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                <i class="fas fa-qrcode text-gray-600"></i>
                            </div>
                            <span class="text-xs text-gray-500 font-mono">{{ Str::limit($p->barcode_data, 15) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('peserta.show', $p->id) }}" 
                               class="text-blue-600 hover:text-blue-900"
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('peserta.edit', $p->id) }}" 
                               class="text-green-600 hover:text-green-900"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('peserta.download-qrcode', $p->id) }}" 
                               class="text-purple-600 hover:text-purple-900"
                               title="Download QR Code">
                                <i class="fas fa-download"></i>
                            </a>
                            <form action="{{ route('peserta.destroy', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Hapus peserta {{ $p->nama }}?')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-users text-2xl mb-2"></i>
                        <p>Belum ada data peserta</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($peserta->hasPages())
<div class="mt-6">
    {{ $peserta->links() }}
</div>
@endif
@endsection