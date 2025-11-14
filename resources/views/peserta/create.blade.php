<!-- resources/views/peserta/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Peserta')
@section('subtitle', 'Tambah peserta baru ke sistem')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('peserta.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap *
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Jabatan
                    </label>
                    <input type="text" 
                           id="jabatan" 
                           name="jabatan" 
                           value="{{ old('jabatan') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Contoh: Ketua DPD,  Anggota Dewan ..">
                </div>



                <!-- Kelas -->
                <div class="md:col-span-2">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas
                    </label>
                    <input type="text" 
                           id="kelas" 
                           name="kelas" 
                           value="{{ old('kelas') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Contoh: ABN-001 ...">
                </div>
            </div>

            <!-- Info Barcode -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <p class="text-blue-700 text-sm">
                        <strong>Barcode akan digenerate otomatis</strong> setelah peserta dibuat
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('peserta.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Peserta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection