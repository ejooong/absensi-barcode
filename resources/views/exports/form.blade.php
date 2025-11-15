@extends('layouts.admin')


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

                <!-- Progress Bar for Absensi -->
                <div id="absensiProgressContainer" class="hidden mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700" id="absensiProgressText">Mempersiapkan...</span>
                        <span class="text-sm text-gray-500" id="absensiProgressPercent">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="absensiProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
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
                <button type="button" 
                        onclick="exportPeserta('excel')"
                        class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-file-excel mr-2"></i>Excel
                </button>
                <button type="button" 
                        onclick="exportPeserta('pdf')"
                        class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </button>
            </div>

            <!-- Progress Bar for Peserta -->
            <div id="pesertaProgressContainer" class="hidden mt-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700" id="pesertaProgressText">Mempersiapkan...</span>
                    <span class="text-sm text-gray-500" id="pesertaProgressPercent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="pesertaProgressBar" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="mt-2 flex justify-between items-center">
                    <span class="text-xs text-gray-500" id="pesertaStatusText">Proses export sedang berjalan...</span>
                    <button onclick="hideProgress('peserta')" class="text-xs text-red-600 hover:text-red-800 font-medium">
                        <i class="fas fa-times mr-1"></i>Batalkan
                    </button>
                </div>
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
        <form action="{{ route('import.peserta') }}" method="POST" enctype="multipart/form-data" id="importForm">
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
                            <th class="px-3 py-2 text-left">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 border">Ahmad Santoso</td>
                            <td class="px-3 py-2 border">Mahasiswa</td>
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
// Progress intervals storage
const progressIntervals = {
    absensi: null,
    peserta: null
};

// Show progress for specific type
function showProgress(type) {
    const container = document.getElementById(`${type}ProgressContainer`);
    container.classList.remove('hidden');
    
    let progress = 0;
    const progressBar = document.getElementById(`${type}ProgressBar`);
    const progressPercent = document.getElementById(`${type}ProgressPercent`);
    const progressText = document.getElementById(`${type}ProgressText`);
    
    // Reset progress
    progressBar.style.width = '0%';
    progressPercent.textContent = '0%';
    progressText.textContent = 'Mempersiapkan...';
    
    // Start progress simulation
    progressIntervals[type] = setInterval(() => {
        if (progress < 85) {
            progress += Math.random() * 3 + 1;
            if (progress > 85) progress = 85;
        }
        
        progressBar.style.width = progress + '%';
        progressPercent.textContent = Math.round(progress) + '%';
        
        // Update status text
        if (progress < 25) {
            progressText.textContent = 'Mempersiapkan data...';
        } else if (progress < 50) {
            progressText.textContent = 'Generate QR Code...';
        } else if (progress < 75) {
            progressText.textContent = 'Menyusun file...';
        } else {
            progressText.textContent = 'Hampir selesai jangan berpindah halaman sampai file terdownload...';
        }
    }, 600);
}

// Hide progress for specific type
function hideProgress(type) {
    if (progressIntervals[type]) {
        clearInterval(progressIntervals[type]);
        progressIntervals[type] = null;
    }
    
    const container = document.getElementById(`${type}ProgressContainer`);
    container.classList.add('hidden');
    
    const progressBar = document.getElementById(`${type}ProgressBar`);
    const progressPercent = document.getElementById(`${type}ProgressPercent`);
    const progressText = document.getElementById(`${type}ProgressText`);
    
    progressBar.style.width = '0%';
    progressPercent.textContent = '0%';
    progressText.textContent = 'Mempersiapkan...';
}

// Complete progress (set to 100%)
function completeProgress(type) {
    if (progressIntervals[type]) {
        clearInterval(progressIntervals[type]);
        progressIntervals[type] = null;
    }
    
    const progressBar = document.getElementById(`${type}ProgressBar`);
    const progressPercent = document.getElementById(`${type}ProgressPercent`);
    const progressText = document.getElementById(`${type}ProgressText`);
    const statusText = document.getElementById(`${type}StatusText`);
    
    progressBar.style.width = '100%';
    progressPercent.textContent = '100%';
    progressText.textContent = 'Selesai!';
    
    if (statusText) {
        statusText.textContent = 'Export berhasil! File sedang didownload...';
        statusText.className = 'text-xs text-green-600 font-medium';
    }
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideProgress(type);
    }, 3000);
}

// Export functions
function exportAbsensi(format) {
    showProgress('absensi');
    
    const form = document.getElementById('exportAbsensiForm');
    const formData = new FormData(form);
    
    let url = '';
    if (format === 'excel') {
        url = '{{ route("export.absensi.excel") }}';
    } else if (format === 'pdf') {
        url = '{{ route("export.absensi.pdf") }}';
    }
    
    const params = new URLSearchParams(formData);
    
    // Create iframe for download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = url + '?' + params.toString();
    
    iframe.onload = function() {
        completeProgress('absensi');
    };
    
    document.body.appendChild(iframe);
    
    // Fallback - complete progress after reasonable time
    setTimeout(() => {
        if (progressIntervals.absensi) {
            completeProgress('absensi');
        }
    }, 10000);
}

function exportPeserta(format) {
    showProgress('peserta');
    
    let url = '';
    if (format === 'excel') {
        url = '{{ route("export.peserta.excel") }}';
    } else if (format === 'pdf') {
        url = '{{ route("export.peserta.pdf") }}';
    }
    
    // Create iframe for download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = url;
    
    iframe.onload = function() {
        completeProgress('peserta');
    };
    
    document.body.appendChild(iframe);
    
    // For peserta export (especially Excel with QR codes), set longer timeout
    const timeout = format === 'excel' ? 30000 : 15000;
    setTimeout(() => {
        if (progressIntervals.peserta) {
            completeProgress('peserta');
        }
    }, timeout);
}

// Prevent accidental navigation
window.addEventListener('beforeunload', function(e) {
    if (progressIntervals.absensi || progressIntervals.peserta) {
        e.preventDefault();
        e.returnValue = 'Proses export sedang berjalan. Yakin ingin meninggalkan halaman?';
        return e.returnValue;
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

/* Smooth progress bar animation */
#absensiProgressBar,
#pesertaProgressBar {
    transition: width 0.3s ease-in-out;
}
</style>
@endsection