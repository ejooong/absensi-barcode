<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Scanner Kehadiran</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/scanner.css') }}">
    <!-- JavaScript Libraries -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/abn.png') }}" 
                     alt="ABSENSI BARCODE" 
                     class="logo-img">
            </div>
            <h1 class="title">SCANNER ABSENSI</h1>
        </div>

        <!-- Kegiatan Info -->
        <div class="kegiatan-card">
            <div class="kegiatan-header">
                <div class="kegiatan-text">
                    <h3 class="kegiatan-title">
                        @if($kegiatanAktif)
                            {{ $kegiatanAktif->program->nama_materi }} - Sesi {{ $kegiatanAktif->sesi_ke }}
                        @else
                            Tidak ada kegiatan aktif
                        @endif
                    </h3>
                    <p class="kegiatan-desc">
                        @if($kegiatanAktif)
                            {{ $kegiatanAktif->materi }}
                        @else
                            Silakan coba lagi nanti
                        @endif
                    </p>
                    <p class="kegiatan-time">
                        @if($kegiatanAktif)
                            {{ \Carbon\Carbon::parse($kegiatanAktif->waktu_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($kegiatanAktif->waktu_akhir)->format('H:i') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="time-display">
                <div id="current-time" class="current-time">00:00:00</div>
                <div id="current-date" class="current-date">Hari, 1 Januari 2024</div>
            </div>
        </div>

        <!-- Scanner Section -->
        <div class="scanner-section">
            <h3 class="section-title">
                <i class="fas fa-camera section-icon"></i>Scanner QR Code
            </h3>

            <div class="scanner-container">
                <div id="reader"></div>
                <div id="scanner-loading" class="scanner-loading">
                    <div class="loading-spinner"></div>
                    <p>Mengaktifkan kamera...</p>
                </div>
                
                <div class="scanner-overlay">
                    <div class="scanning-line"></div>
                    <div class="scanner-frame"></div>
                </div>
            </div>

            <!-- Scanner Controls -->
            <div class="controls">
                <button id="btn-start-scanner" class="btn btn-success">
                    <i class="fas fa-play btn-icon"></i>Mulai Scan
                </button>
                <button id="btn-stop-scanner" class="btn btn-danger hidden">
                    <i class="fas fa-stop btn-icon"></i>Stop Scan
                </button>
            </div>

            <!-- Camera Controls -->
            <div class="camera-controls">
                <button id="btn-switch-camera" class="btn btn-secondary" title="Ganti kamera">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>

            <div class="tips">
                Tips: Arahkan kamera ke QR Code, scanner akan bekerja otomatis
            </div>
        </div>
    </div>

    <!-- Modal Success -->
    <div id="success-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon success">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="modal-title">Absensi Berhasil!</h2>
                <p class="modal-subtitle">Data absensi telah tercatat dengan baik</p>
            </div>
            <div class="modal-body">
                <div class="info-grid" id="success-modal-content">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-primary">
                    <i class="fas fa-check btn-icon"></i>Oke
                </button>
                <button class="btn-modal btn-modal-secondary">
                    <i class="fas fa-times btn-icon"></i>Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Already Absen -->
    <div id="already-absen-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon warning">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2 class="modal-title">Sudah Absen</h2>
                <p class="modal-subtitle">Anda sudah melakukan absensi untuk sesi ini</p>
            </div>
            <div class="modal-body">
                <div class="info-grid" id="already-absen-modal-content">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-primary">
                    <i class="fas fa-check btn-icon"></i>Mengerti
                </button>
            </div>
        </div>
    </div>

<script>
    // CSRF Token dari Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    let html5QrcodeScanner = null;
    let currentCameraId = null;
    let isScannerActive = false;
    
    // Variabel untuk membatasi scan
    let lastScannedCode = '';
    let lastScanTime = 0;
    let scanCooldown = 3000; // 3 detik cooldown antara scan

    // Safe vibrate function
    function vibrate(pattern = 100) {
        try {
            if ('vibrate' in navigator) {
                if (document.body.classList.contains('user-interacted')) {
                    navigator.vibrate(pattern);
                }
            }
        } catch (error) {
            console.log('Vibration not supported or blocked');
        }
    }

    // Mark user interaction
    function markUserInteraction() {
        if (!document.body.classList.contains('user-interacted')) {
            document.body.classList.add('user-interacted');
        }
    }

    // Modal functions
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            console.log('Modal shown:', modalId);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function closeAllModals() {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeAllModals();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Start scanner
    async function startScanner() {
        try {
            document.getElementById('btn-start-scanner').classList.add('hidden');
            document.getElementById('btn-stop-scanner').classList.remove('hidden');
            document.getElementById('scanner-loading').classList.remove('hidden');

            // Stop scanner sebelumnya jika ada
            if (html5QrcodeScanner) {
                await html5QrcodeScanner.clear().catch(console.error);
            }

            // Konfigurasi scanner
            const config = {
                fps: 10,
                qrbox: 250,
                aspectRatio: 1.0
            };

            // Buat scanner baru
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                config,
                false
            );

            // Render scanner
            await html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            
            isScannerActive = true;
            document.getElementById('scanner-loading').classList.add('hidden');
            
        } catch (error) {
            console.error('Scanner error:', error);
            handleScannerError(error);
            document.getElementById('btn-start-scanner').classList.remove('hidden');
            document.getElementById('btn-stop-scanner').classList.add('hidden');
            document.getElementById('scanner-loading').classList.add('hidden');
        }
    }

    // Stop scanner
    async function stopScanner() {
        if (html5QrcodeScanner && isScannerActive) {
            try {
                await html5QrcodeScanner.clear();
            } catch (error) {
                console.error('Error stopping scanner:', error);
            }
            html5QrcodeScanner = null;
        }
        isScannerActive = false;
        document.getElementById('btn-start-scanner').classList.remove('hidden');
        document.getElementById('btn-stop-scanner').classList.add('hidden');
    }

    // Switch camera
    async function switchCamera() {
        if (!isScannerActive) return;
        
        try {
            const Html5Qrcode = window.Html5Qrcode;
            const cameras = await Html5Qrcode.getCameras();
            if (cameras.length < 2) {
                alert('Hanya ada 1 kamera yang terdeteksi');
                return;
            }
            
            const currentIndex = cameras.findIndex(cam => cam.id === currentCameraId);
            const nextIndex = (currentIndex + 1) % cameras.length;
            currentCameraId = cameras[nextIndex].id;
            
            await stopScanner();
            setTimeout(() => startScanner(), 500);
            
        } catch (error) {
            console.error('Error switching camera:', error);
        }
    }

    // Scan success handler dengan cooldown
    function onScanSuccess(decodedText, decodedResult) {
        const now = Date.now();
        
        // Cek cooldown dan duplicate scan
        if (now - lastScanTime < scanCooldown && decodedText === lastScannedCode) {
            console.log('Scan diabaikan: cooldown atau duplikat');
            return;
        }
        
        lastScannedCode = decodedText;
        lastScanTime = now;
        
        console.log('Scan success:', decodedText);
        
        // Visual feedback
        const reader = document.getElementById('reader');
        if (reader) {
            reader.style.border = '3px solid #10B981';
            setTimeout(() => { reader.style.border = 'none'; }, 300);
        }
        
        vibrate([100, 50, 100]);
        processBarcode(decodedText);
    }

    // Scan failure handler
    function onScanFailure(error) {
        // Biarkan kosong - library akan terus mencoba scan
    }

    // Process barcode data
    function processBarcode(barcodeData) {
        console.log('🔄 Memproses barcode:', barcodeData);
        
        if (!barcodeData || typeof barcodeData !== 'string') {
            alert('Data barcode tidak valid.');
            return;
        }

        const formData = new FormData();
        formData.append('barcode_data', barcodeData);
        formData.append('_token', csrfToken);

        console.log('🔄 Mengirim request ke server dengan barcode:', barcodeData);

        fetch("{{ route('absensi.process') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async response => {
            const text = await response.text();
            console.log('📥 Raw response dari server:', text);
            
            let data;
            try { 
                data = JSON.parse(text); 
                console.log('✅ JSON parsed successfully:', data);
            } catch(e) { 
                console.error('❌ JSON parse error:', e);
                data = { success: false, message: 'Format response tidak valid' }; 
            }
            
            return data;
        })
        .then(data => {
            console.log('🎯 Processed data:', data);
            if (data && data.success) {
                handleProcessResult(true, data);
            } else {
                handleProcessResult(false, data);
            }
        })
        .catch(error => {
            console.error('💥 Network error:', error);
            alert('Koneksi jaringan bermasalah: ' + error.message);
        });
    }

    // Handle process result
    function handleProcessResult(success, data) {
        console.log('Handle process result:', { success, data });
        
        if (success) {
            showSuccessModal(data.data);
            vibrate([100, 50, 100]);
        } else {
            if (data.message && data.message.includes('sudah absen')) {
                const absenData = data.data || extractDataFromMessage(data);
                showAlreadyAbsenModal(absenData);
                vibrate(200);
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            }
        }
    }

    // Helper function untuk extract data dari response
    function extractDataFromMessage(data) {
        return {
            nama: data.nama || data.data?.nama || '-',
            jabatan: data.jabatan || data.data?.jabatan || '-',
            kelas: data.kelas || data.data?.kelas || '-',
            program: data.program || data.data?.program || '-',
            sesi: data.sesi || data.data?.sesi || '-',
            status: data.status || data.data?.status || 'hadir',
            waktu: data.waktu || data.data?.waktu || '-',
            tanggal: data.tanggal || data.data?.tanggal || '-'
        };
    }

    // Show success modal
    function showSuccessModal(data) {
        const modalContent = document.getElementById('success-modal-content');
        if (!modalContent) {
            console.error('Modal content not found');
            return;
        }
        
        modalContent.innerHTML = `
            <div class="info-item">
                <span class="info-label">Nama:</span>
                <span class="info-value" style="color: #065F46; font-weight: 700;">${data.nama || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Jabatan:</span>
                <span class="info-value">${data.jabatan || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kelas:</span>
                <span class="info-value">${data.kelas || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Program:</span>
                <span class="info-value">${data.program || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Sesi:</span>
                <span class="info-value">${data.sesi || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="status-badge ${data.status === 'hadir' ? 'status-hadir' : 'status-terlambat'}">${data.status || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu:</span>
                <span class="info-value" style="font-family: monospace;">${data.waktu || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">${data.tanggal || '-'}</span>
            </div>
        `;
        showModal('success-modal');
    }

    // Show already absen modal
    function showAlreadyAbsenModal(data) {
        console.log('Showing already absen modal with data:', data);
        
        const modalContent = document.getElementById('already-absen-modal-content');
        if (!modalContent) {
            console.error('Already absen modal content not found');
            return;
        }
        
        const safeData = {
            nama: data?.nama || '-',
            jabatan: data?.jabatan || '-',
            kelas: data?.kelas || '-',
            program: data?.program || '-',
            sesi: data?.sesi || '-',
            status: data?.status || 'hadir',
            waktu: data?.waktu || '-',
            tanggal: data?.tanggal || '-'
        };

        modalContent.innerHTML = `
            <div class="info-item">
                <span class="info-label">Nama:</span>
                <span class="info-value">${safeData.nama}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Jabatan:</span>
                <span class="info-value">${safeData.jabatan}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kelas:</span>
                <span class="info-value">${safeData.kelas}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Program:</span>
                <span class="info-value">${safeData.program}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Sesi:</span>
                <span class="info-value">${safeData.sesi}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="status-badge ${safeData.status === 'hadir' ? 'status-hadir' : 'status-terlambat'}">${safeData.status}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu Absen:</span>
                <span class="info-value" style="font-family: monospace;">${safeData.waktu}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">${safeData.tanggal}</span>
            </div>
        `;
        showModal('already-absen-modal');
    }

    // Handle scanner error
    function handleScannerError(error) {
        const message = error.message || String(error);
        const lowered = message.toLowerCase();

        let errorMessage = 'Gagal mengakses kamera. ';
        if (lowered.includes('notallowed') || lowered.includes('permission')) {
            errorMessage = 'Izin kamera ditolak. Buka pengaturan browser untuk mengizinkan kamera.';
        } else if (lowered.includes('notfound') || lowered.includes('no device')) {
            errorMessage = 'Kamera tidak ditemukan di perangkat ini.';
        } else if (lowered.includes('notsupported')) {
            errorMessage = 'Browser tidak mendukung akses kamera.';
        } else {
            errorMessage += message;
        }

        alert(errorMessage);
    }

    // Update waktu real-time
    function updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent =
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('current-date').textContent =
            now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Bind event listeners
        document.getElementById('btn-start-scanner').addEventListener('click', startScanner);
        document.getElementById('btn-stop-scanner').addEventListener('click', stopScanner);
        document.getElementById('btn-switch-camera').addEventListener('click', switchCamera);
        
        // Modal buttons
        document.querySelector('#success-modal .btn-modal-primary').addEventListener('click', () => closeModal('success-modal'));
        document.querySelector('#success-modal .btn-modal-secondary').addEventListener('click', () => closeModal('success-modal'));
        document.querySelector('#already-absen-modal .btn-modal-primary').addEventListener('click', () => closeModal('already-absen-modal'));

        // Mark user interaction
        document.addEventListener('click', markUserInteraction);
        document.addEventListener('touchstart', markUserInteraction);

        // Auto-start scanner
        setTimeout(() => {
            if (!document.hidden) {
                startScanner();
            }
        }, 1000);

        // Handle page visibility
        document.addEventListener('visibilitychange', function() { 
            if (document.hidden && isScannerActive) {
                stopScanner();
            } else if (!document.hidden && !isScannerActive) {
                setTimeout(() => startScanner(), 500);
            }
        });

        // Start time update
        setInterval(updateTime, 1000);
        updateTime();
    });
</script>
</body>
</html>