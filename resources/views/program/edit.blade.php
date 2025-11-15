@extends('layouts.admin')



@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('program.update', $program->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nama Materi -->
                <div>
                    <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Program <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_materi" 
                           id="nama_materi"
                           value="{{ old('nama_materi', $program->nama_materi) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           placeholder="Contoh: Pemrograman Web, Basis Data, etc."
                           required>
                    @error('nama_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('program.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Update Program
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection