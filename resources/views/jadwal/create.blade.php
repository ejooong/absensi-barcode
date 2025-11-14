<!-- resources/views/jadwal/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Jadwal')
@section('subtitle', 'Buat jadwal sesi perkuliahan baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('jadwal.store') }}" id="jadwalForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mata Kuliah -->
                <div class="md:col-span-2">
                    <label for="mata_kuliah_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Kuliah *
                    </label>
                    <select id="mata_kuliah_id" 
                            name="mata_kuliah_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('mata_kuliah_id') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_materi }} - {{ $mk->nama_materi }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sesi -->
                <div>
                    <label for="sesi_ke" class="block text-sm font-medium text-gray-700 mb-2">
                        Sesi Ke- *
                    </label>
                    <input type="number" 
                           id="sesi_ke" 
                           name="sesi_ke" 
                           value="{{ old('sesi_ke') }}"
                           min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('sesi_ke') border-red-500 @enderror"
                           placeholder="Contoh: 1"
                           required>
                    @error('sesi_ke')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Materi -->
                <div class="md:col-span-2">
                    <label for="materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Materi Pembahasan *
                    </label>
                    <input type="text" 
                           id="materi" 
                           name="materi" 
                           value="{{ old('materi') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('materi') border-red-500 @enderror"
                           placeholder="Contoh: Pengenalan HTML & CSS"
                           required>
                    @error('materi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Mulai *
                    </label>
                    <input type="datetime-local" 
                           id="waktu_mulai" 
                           name="waktu_mulai" 
                           value="{{ old('waktu_mulai') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('waktu_mulai') border-red-500 @enderror"
                           required>
                    @error('waktu_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Akhir -->
                <div>
                    <label for="waktu_akhir" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Akhir *
                    </label>
                    <input type="datetime-local" 
                           id="waktu_akhir" 
                           name="waktu_akhir" 
                           value="{{ old('waktu_akhir') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('waktu_akhir') border-red-500 @enderror"
                           required>
                    @error('waktu_akhir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Jadwal -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <p class="text-blue-700 text-sm">
                        <strong>Pastikan waktu tidak bentrok</strong> dengan jadwal lain. Sistem akan mengecek otomatis.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('jadwal.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default waktu (1 jam dari sekarang)
    const now = new Date();
    const oneHourLater = new Date(now.getTime() + 60 * 60 * 1000);
    
    // Format untuk datetime-local input
    function formatDateTime(date) {
        return date.toISOString().slice(0, 16);
    }
    
    // Set default values jika belum ada
    if (!document.getElementById('waktu_mulai').value) {
        document.getElementById('waktu_mulai').value = formatDateTime(now);
    }
    if (!document.getElementById('waktu_akhir').value) {
        document.getElementById('waktu_akhir').value = formatDateTime(oneHourLater);
    }
    
    // Validasi waktu
    document.getElementById('jadwalForm').addEventListener('submit', function(e) {
        const mulai = new Date(document.getElementById('waktu_mulai').value);
        const akhir = new Date(document.getElementById('waktu_akhir').value);
        
        if (akhir <= mulai) {
            e.preventDefault();
            alert('Waktu akhir harus setelah waktu mulai!');
            return false;
        }
        
        // Cek apakah waktu sudah lewat
        if (mulai < new Date()) {
            if (!confirm('Waktu mulai sudah lewat. Yakin ingin membuat jadwal?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endsection