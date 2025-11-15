@extends('layouts.admin')



@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('program.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Hanya Nama Materi Saja -->
                <div>
                    <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Program <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_materi" 
                           id="nama_materi"
                           value="{{ old('nama_materi') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           placeholder="Contoh: Pemrograman Web, Basis Data, etc."
                           required>
                    @error('nama_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Data -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Preview Data:</h4>
                    <div class="text-sm text-blue-700">
                        <div id="preview-nama">Nama: <span>-</span></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('program.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Program
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
            <div>
                <h4 class="text-sm font-medium text-yellow-800">Tips:</h4>
                <ul class="text-sm text-yellow-700 mt-1 list-disc list-inside space-y-1">
                    <li>Gunakan nama yang jelas dan deskriptif</li>
                    <li>Pastikan nama Program belum pernah digunakan sebelumnya</li>
                    <li>Setelah membuat Program, Anda bisa menambahkan Kegiatan sesi</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Real-time preview hanya untuk nama
    document.addEventListener('DOMContentLoaded', function() {
        const namaInput = document.getElementById('nama_materi');
        const previewNama = document.getElementById('preview-nama');

        function updatePreview() {
            const nama = namaInput.value || '-';
            previewNama.innerHTML = `Nama: <span class="font-medium">${nama}</span>`;
        }

        namaInput.addEventListener('input', updatePreview);
        
        // Initial preview
        updatePreview();
    });
</script>
@endsection