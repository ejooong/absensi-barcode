@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Daftar Peserta</h2>
        <p class="text-sm text-gray-600 mt-1">Total: {{ $peserta->count() }} peserta</p>
    </div>
    <div class="flex space-x-3">
        <!-- Tombol Hapus Semua -->
        @if($peserta->count() > 0)
        <button type="button" 
                onclick="confirmDeleteAll()"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Semua ({{ $peserta->count() }})
        </button>
        @endif
        
        <!-- Tombol Tambah Peserta -->
        <a href="{{ route('peserta.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-user-plus mr-2"></i>Tambah Peserta
        </a>
        

    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peserta as $index => $p)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            @if($peserta instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ ($peserta->currentPage() - 1) * $peserta->perPage() + $index + 1 }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                    </td>
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

<!-- Pagination (jika menggunakan pagination) -->
@if($peserta instanceof \Illuminate\Pagination\LengthAwarePaginator && $peserta->hasPages())
<div class="mt-6">
    {{ $peserta->links() }}
</div>
@endif

<!-- Warning jika data terlalu banyak -->
@if(!($peserta instanceof \Illuminate\Pagination\LengthAwarePaginator) && $peserta->count() > 50)
<div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
        <span class="text-yellow-800 text-sm">
            Menampilkan semua {{ $peserta->count() }} data peserta. Untuk performa yang lebih baik, pertimbangkan menggunakan pagination.
        </span>
    </div>
</div>
@endif

<!-- Form untuk Hapus Semua (Hanya SATU form) -->
<form id="deleteAllForm" action="{{ route('peserta.delete-all') }}" method="POST" style="display: none;">
    @csrf
    <!-- HAPUS @method('DELETE') karena route menggunakan POST -->
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteAll() {
    const count = {{ $peserta->count() }};
    
    if (count === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Tidak ada data',
            text: 'Tidak ada data peserta untuk dihapus.'
        });
        return;
    }
    
    Swal.fire({
        title: 'Hapus Semua Data?',
        html: `Anda akan menghapus <b>${count} data peserta</b>. Tindakan ini tidak dapat dibatalkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('deleteAllForm').submit();
        }
    });
}

function showLoading() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loadingOverlay';
    loadingDiv.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
            <div class="bg-white p-6 rounded-lg shadow-lg flex items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                <span class="text-gray-700">Menghapus semua data peserta...</span>
            </div>
        </div>
    `;
    document.body.appendChild(loadingDiv);
}

document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
});
</script>

<style>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endsection