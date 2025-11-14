@extends('layouts.admin')

@section('title', 'Export Data')
@section('subtitle', 'Export laporan dan data peserta')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Export Absensi -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-download mr-2 text-blue-600"></i>Export Laporan Absensi
        </h3>
        
        <form id="exportAbsensiForm">
            @csrf
            <div class="space-y-4">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date" 
                           id="tanggal" 
                           name="tanggal" 
                           value="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- HAPUS FILTER KELOMPOK --}}

                <!-- Kelas -->
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Filter Kelas (Opsional)
                    </label>
                    <select id="kelas" 
                            name="kelas"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}">{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Export Buttons -->
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button type="button" 
                            onclick="exportAbsensi('excel')"
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-file-excel mr-2"></i>Excel
                    </button>
                    <button type="button" 
                            onclick="exportAbsensi('pdf')"
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Export Peserta -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-users mr-2 text-green-600"></i>Export Data Peserta
        </h3>
        
        <div class="space-y-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-blue-700 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Export semua data peserta termasuk barcode untuk backup
                </p>
            </div>

            <!-- Export Buttons -->
            <div class="grid grid-cols-2 gap-3 pt-4">
                <a href="{{ route('export.peserta.excel') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-file-excel mr-2"></i>Excel
                </a>
                <a href="{{ route('export.peserta.pdf') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Import Section -->
<div class="mt-8 bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-upload mr-2 text-purple-600"></i>Import Data Peserta
    </h3>
    
    <div class="max-w-2xl">
        <form action="{{ route('import.peserta') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        File Excel (.xlsx, .xls, .csv)
                    </label>
                    <input type="file" 
                           id="file" 
                           name="file" 
                           accept=".xlsx,.xls,.csv"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="text-sm text-gray-500 mt-1">
                        Format file harus sesuai template. 
                        <a href="{{ route('export.peserta.template') }}" class="text-blue-600 hover:text-blue-800">
                            Download template
                        </a>
                    </p>
                </div>

                <!-- Import Options -->
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="update_existing" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Update data yang sudah ada</span>
                    </label>
                </div>

                <!-- Import Button -->
                <div>
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-6 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-upload mr-2"></i>Import Data
                    </button>
                </div>
            </div>
        </form>

        <!-- Format Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-800 mb-2">Format File Excel:</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-3 py-2 text-left">Nama*</th>
                            <th class="px-3 py-2 text-left">Jabatan</th>
                            {{-- HAPUS KOLOM KELOMPOK --}}
                            <th class="px-3 py-2 text-left">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 border">Ahmad Santoso</td>
                            <td class="px-3 py-2 border">Mahasiswa</td>
                            {{-- HAPUS KOLOM KELOMPOK --}}
                            <td class="px-3 py-2 border">TI-1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-gray-600 mt-2">*Kolom nama wajib diisi</p>
        </div>
    </div>
</div>

<script>
function exportAbsensi(format) {
    const form = document.getElementById('exportAbsensiForm');
    const formData = new FormData(form);
    
    let url = '';
    if (format === 'excel') {
        url = '{{ route("export.absensi.excel") }}';
    } else if (format === 'pdf') {
        url = '{{ route("export.absensi.pdf") }}';
    }
    
    // Add form data to URL
    const params = new URLSearchParams(formData);
    window.location.href = url + '?' + params.toString();
}
</script>
@endsection