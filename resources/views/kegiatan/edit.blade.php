@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Kegiatan</h2>
            <p class="text-gray-600">Ubah informasi kegiatan yang sudah ada</p>
        </div>

        <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Program -->
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program_id" 
                            id="program_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            required>
                        <option value="">Pilih Program</option>
                        @foreach($program as $prog)
                            <option value="{{ $prog->id }}" 
                                {{ old('program_id', $kegiatan->program_id) == $prog->id ? 'selected' : '' }}>
                                {{ $prog->nama_materi}}
                            </option>
                        @endforeach
                    </select>
                    @error('program_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sesi -->
                <div>
                    <label for="sesi_ke" class="block text-sm font-medium text-gray-700 mb-2">
                        Sesi Ke <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="sesi_ke" 
                           id="sesi_ke"
                           value="{{ old('sesi_ke', $kegiatan->sesi_ke) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           placeholder="Contoh: 1, 2, 3, ..."
                           min="1"
                           required>
                    @error('sesi_ke')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Materi Pembahasan -->
                <div>
                    <label for="materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Materi Pembahasan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="materi" 
                           id="materi"
                           value="{{ old('materi', $kegiatan->materi) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           placeholder="Contoh: Pengenalan Kebijakan Publik, dll.."
                           required>
                    @error('materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="waktu_mulai" 
                           id="waktu_mulai"
                           value="{{ old('waktu_mulai', $kegiatan->waktu_mulai->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           required>
                    @error('waktu_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Akhir -->
                <div>
                    <label for="waktu_akhir" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Akhir <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="waktu_akhir" 
                           id="waktu_akhir"
                           value="{{ old('waktu_akhir', $kegiatan->waktu_akhir->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                           required>
                    @error('waktu_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Data -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Preview Data:</h4>
                    <div class="text-sm text-blue-700 space-y-1">
                        <div id="preview-program">Program: <span class="font-medium">-</span></div>
                        <div id="preview-sesi">Sesi: <span class="font-medium">-</span></div>
                        <div id="preview-materi">Materi: <span class="font-medium">-</span></div>
                        <div id="preview-waktu">Waktu: <span class="font-medium">-</span></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('kegiatan.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Update Kegiatan
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
                <h4 class="text-sm font-medium text-yellow-800">Tips Edit Kegiatan:</h4>
                <ul class="text-sm text-yellow-700 mt-1 list-disc list-inside space-y-1">
                    <li>Pastikan waktu tidak bentrok dengan kegiatan lain</li>
                    <li>Periksa kembali materi yang akan dibahas</li>
                    <li>Jika sudah ada absensi, perubahan waktu mungkin mempengaruhi data kehadiran</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Real-time preview
    document.addEventListener('DOMContentLoaded', function() {
        const programSelect = document.getElementById('program_id');
        const sesiInput = document.getElementById('sesi_ke');
        const materiInput = document.getElementById('materi');
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuAkhirInput = document.getElementById('waktu_akhir');
        
        const previewProgram = document.getElementById('preview-program');
        const previewSesi = document.getElementById('preview-sesi');
        const previewMateri = document.getElementById('preview-materi');
        const previewWaktu = document.getElementById('preview-waktu');

        function getSelectedProgramText() {
            const selectedOption = programSelect.options[programSelect.selectedIndex];
            return selectedOption.textContent || '-';
        }

        function formatDateTime(datetimeStr) {
            if (!datetimeStr) return '-';
            const date = new Date(datetimeStr);
            return date.toLocaleDateString('id-ID', { 
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function updatePreview() {
            const program = getSelectedProgramText();
            const sesi = sesiInput.value || '-';
            const materi = materiInput.value || '-';
            const waktuMulai = waktuMulaiInput.value;
            const waktuAkhir = waktuAkhirInput.value;
            
            previewProgram.innerHTML = `Program: <span class="font-medium">${program}</span>`;
            previewSesi.innerHTML = `Sesi: <span class="font-medium">${sesi}</span>`;
            previewMateri.innerHTML = `Materi: <span class="font-medium">${materi}</span>`;
            
            if (waktuMulai && waktuAkhir) {
                previewWaktu.innerHTML = `Waktu: <span class="font-medium">${formatDateTime(waktuMulai)} - ${formatDateTime(waktuAkhir)}</span>`;
            } else {
                previewWaktu.innerHTML = `Waktu: <span class="font-medium">-</span>`;
            }
        }

        programSelect.addEventListener('change', updatePreview);
        sesiInput.addEventListener('input', updatePreview);
        materiInput.addEventListener('input', updatePreview);
        waktuMulaiInput.addEventListener('input', updatePreview);
        waktuAkhirInput.addEventListener('input', updatePreview);
        
        // Initial preview
        updatePreview();
    });
</script>
@endsection