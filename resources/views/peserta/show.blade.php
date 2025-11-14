<!-- resources/views/peserta/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Peserta')
@section('subtitle', 'Informasi lengkap peserta')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">{{ $peserta->nama }}</h1>
                    <p class="text-blue-100">{{ $peserta->jabatan ?? 'Tidak ada jabatan' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-90">ID: {{ $peserta->id }}</div>
                    <div class="text-sm opacity-90">{{ optional($peserta->created_at)->format('d M Y') ?? '-' }}</div>

                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informasi Peserta -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Peserta</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">{{ $peserta->nama }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Jabatan:</span>a
                            <span class="font-medium">{{ $peserta->jabatan ?? '-' }}</span>
                        </div>
                            
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium">{{ $peserta->kelas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Barcode Data:</span>
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                {{ $peserta->barcode_data }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
<div class="mb-4">
    @if(!empty($peserta->barcode_data))
        {!! QrCode::size(200)->generate($peserta->barcode_data) !!}
    @else
        <div class="w-48 h-48 flex items-center justify-center rounded-md border-2 border-dashed text-sm text-gray-500">
            Barcode belum tersedia
        </div>
    @endif
</div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                <a href="{{ route('peserta.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Kembali
                </a>
                <a href="{{ route('peserta.edit', $peserta->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection