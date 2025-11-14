<!-- resources/views/peserta/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Peserta')
@section('subtitle', 'Update data peserta')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Edit Peserta</h1>
                    <p class="text-blue-100">Update informasi peserta</p>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-90">ID: {{ $peserta->id }}</div>
                    <div class="text-sm opacity-90">
                        {{ $peserta->created_at ? $peserta->created_at->format('d M Y') : 'Tanggal tidak tersedia' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" 
                               name="nama" 
                               id="nama"
                               value="{{ old('nama', $peserta->nama) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" 
                               name="jabatan" 
                               id="jabatan"
                               value="{{ old('jabatan', $peserta->jabatan) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Contoh: Mahasiswa, Asisten Lab, dll">
                        @error('jabatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

  

                    <!-- Kelas -->
                    <div class="col-span-2">
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <input type="text" 
                               name="kelas" 
                               id="kelas"
                               value="{{ old('kelas', $peserta->kelas) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Contoh: TI-1, SI-2, dll">
                        @error('kelas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barcode Data (readonly) -->
                    <div class="col-span-2">
                        <label for="barcode_data" class="block text-sm font-medium text-gray-700 mb-2">Barcode Data</label>
                        <input type="text" 
                               name="barcode_data" 
                               id="barcode_data"
                               value="{{ $peserta->barcode_data }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm"
                               readonly
                               disabled>
                        <p class="mt-1 text-xs text-gray-500">Barcode data tidak dapat diubah</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('peserta.show', $peserta->id) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Update Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection