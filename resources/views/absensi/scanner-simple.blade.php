<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Scanner Absensi - Absensi Barcode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Responsive CSS untuk Web dan Mobile -->
    <style>
        /* Reset dan Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background: linear-gradient(135deg, #3B82F6, #8B5CF6); 
            min-height: 100vh;
            padding: 0;
        }
        .container { 
            max-width: 100%;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header { 
            text-align: center; 
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }
        .logo-container {
            background: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .logo-icon {
            color: #2563EB;
            font-size: 2rem;
        }
        .title {
            font-size: 1.75rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.25rem;
        }
        .subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        .jadwal-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .jadwal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .jadwal-text { flex: 1; }
        .jadwal-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .jadwal-desc {
            font-size: 0.85rem;
            color: #6B7280;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .jadwal-time {
            font-size: 0.9rem;
            color: #2563EB;
            font-weight: 600;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #10B981;
            margin-left: 0.75rem;
            flex-shrink: 0;
            margin-top: 0.25rem;
        }
        .status-indicator.inactive { background: #EF4444; }
        .time-display { text-align: right; padding-top: 0.75rem; border-top: 1px solid #F3F4F6; }
        .current-time {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 0.25rem;
        }
        .current-date { font-size: 0.85rem; color: #6B7280; }

        .main-content { display: flex; flex-direction: column; gap: 1.25rem; flex: 1; }

        .scanner-section {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .section-icon { margin-right: 0.5rem; font-size: 1.1rem; }
        .camera-blue { color: #2563EB; }
        .camera-purple { color: #8B5CF6; }
        .camera-green { color: #059669; }

        .scanner-container { 
            position: relative; 
            width: 100%; 
            margin-bottom: 1rem; 
            border-radius: 0.75rem;
            overflow: hidden;
            background: #000;
            min-height: 300px;
        }
        #reader { 
            width: 100% !important; 
            height: 100% !important;
        }
        .scanner-overlay { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            pointer-events: none; 
            z-index: 10;
        }
        .scanning-line { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 3px; 
            background: linear-gradient(90deg, transparent, #3B82F6, transparent); 
            animation: scan 2s infinite; 
        }
        @keyframes scan { 
            0% { transform: translateY(0); } 
            50% { transform: translateY(100%); } 
            100% { transform: translateY(0); } 
        }
        .scanner-frame { 
            position: absolute; 
            top: 10%; 
            left: 10%; 
            width: 80%; 
            height: 80%; 
            border: 2px solid rgba(59, 130, 246, 0.4); 
            border-radius: 0.5rem; 
        }

        .controls-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
        .camera-controls { display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn { padding: 0.875rem 1rem; border-radius: 0.75rem; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; min-height: 48px; }
        .btn-icon { margin-right: 0.5rem; font-size: 0.9rem; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #DC2626; color: white; }
        .btn-danger:hover { background: #B91C1C; }
        .btn-secondary { background: #6B7280; color: white; padding: 0.75rem; }
        .btn-secondary:hover { background: #4B5563; }
        .btn-primary { background: #2563EB; color: white; }
        .btn-primary:hover { background: #1D4ED8; }
        .hidden { display: none !important; }

        .input-section { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .input-group { display: flex; flex-direction: column; gap: 0.75rem; }
        .barcode-input { width: 100%; padding: 1rem; border: 2px solid #E5E7EB; border-radius: 0.75rem; font-family: monospace; font-size: 1.1rem; text-align: center; transition: border-color 0.2s; }
        .barcode-input:focus { outline: none; border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        .result-section { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        #result-container { min-height: 140px; display: flex; align-items: center; justify-content: center; }
        .result-placeholder { text-align: center; color: #9CA3AF; }
        .placeholder-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }

        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem; }
        .stat-card { padding: 1rem; border-radius: 0.75rem; text-align: center; }
        .stat-success { background: #ECFDF5; border: 1px solid #A7F3D0; }
        .stat-failed { background: #FEF2F2; border: 1px solid #FECACA; }
        .stat-number { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.25rem; }
        .stat-success .stat-number { color: #059669; }
        .stat-failed .stat-number { color: #DC2626; }
        .stat-label { font-size: 0.8rem; font-weight: 600; }
        .stat-success .stat-label { color: #065F46; }
        .stat-failed .stat-label { color: #991B1B; }

        .navigation { text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); }
        .nav-link { color: white; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.5rem; transition: background 0.2s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        .nav-icon { margin-right: 0.5rem; }

        .result-success { background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 0.75rem; padding: 1.25rem; text-align: center; }
        .result-error { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 0.75rem; padding: 1.25rem; text-align: center; }
        .result-icon { width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .success-icon { background: #D1FAE5; color: #059669; }
        .error-icon { background: #FEE2E2; color: #DC2626; }
        .result-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
        .result-success .result-title { color: #065F46; }
        .result-error .result-title { color: #991B1B; }
        .result-details { display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; }
        .detail-label { color: #6B7280; }
        .detail-value { font-weight: 500; }
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-hadir { background: #D1FAE5; color: #065F46; }
        .status-terlambat { background: #FEF3C7; color: #92400E; }

        .scanner-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.8);
            color: white;
            z-index: 5;
        }
        .loading-spinner { width: 2.5rem; height: 2.5rem; border: 3px solid transparent; border-top: 3px solid white; border-radius: 50%; margin: 0 auto 0.75rem; animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }

        .modal-icon.success {
            background: #D1FAE5;
            color: #059669;
        }

        .modal-icon.warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            color: #6B7280;
            font-size: 1rem;
        }

        .modal-body {
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6B7280;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .modal-footer {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-modal {
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 120px;
        }

        .btn-modal-primary {
            background: #2563EB;
            color: white;
        }

        .btn-modal-primary:hover {
            background: #1D4ED8;
        }

        .btn-modal-secondary {
            background: #6B7280;
            color: white;
        }

        .btn-modal-secondary:hover {
            background: #4B5563;
        }

        /* Scan Limit Warning */
        .scan-limit-warning {
            background: #FEF3C7;
            border: 1px solid #F59E0B;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            display: none;
        }

        .scan-limit-warning.active {
            display: block;
        }

        .warning-icon {
            color: #D97706;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 640px) {
            .container { max-width: 640px; padding: 1.5rem; }
            .controls-grid { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (min-width: 768px) {
            .container { max-width: 768px; }
            .main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            .scanner-section { grid-column: 1 / -1; }
            .modal-content { padding: 2.5rem; }
        }
        @media (min-width: 1024px) {
            .container { max-width: 1024px; }
            .main-content { grid-template-columns: 2fr 1fr; }
            .scanner-section { grid-column: 1; }
            .input-section { grid-column: 2; grid-row: 1; }
            .result-section { grid-column: 2; grid-row: 2; }
        }
        @media (max-height: 700px) {
            .container { padding: 1rem; }
            .header { margin-bottom: 1rem; }
            .jadwal-card { margin-bottom: 1rem; padding: 1rem; }
            .scanner-section, .input-section, .result-section { padding: 1rem; }
            .modal-content { padding: 1.5rem; }
        }
        @media (max-width: 480px) {
            .modal-content { margin: 1rem; padding: 1.5rem; }
            .modal-footer { flex-direction: column; }
            .btn-modal { width: 100%; }
        }
    </style>

    <!-- Scanner Library yang lebih stabil -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <i class="fas fa-qrcode logo-icon"></i>
            </div>
            <h1 class="title">SCANNER ABSENSI</h1>
            <p class="subtitle">Scan QR Code untuk absensi (Laptop & Mobile)</p>
        </div>

        <!-- Jadwal Info -->
        <div class="jadwal-card">
            <div class="jadwal-header">
                <div class="jadwal-text">
                    <h3 class="jadwal-title">
                        @if($jadwalAktif)
                            {{ $jadwalAktif->mataKuliah->nama_materi }} - Sesi {{ $jadwalAktif->sesi_ke }}
                        @else
                            Tidak ada jadwal aktif
                        @endif
                    </h3>
                    <p class="jadwal-desc">
                        @if($jadwalAktif)
                            {{ $jadwalAktif->materi }}
                        @else
                            Silakan coba lagi nanti
                        @endif
                    </p>
                    <p class="jadwal-time">
                        @if($jadwalAktif)
                            {{ \Carbon\Carbon::parse($jadwalAktif->waktu_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwalAktif->waktu_akhir)->format('H:i') }}
                        @endif
                    </p>
                </div>
                <div class="status-indicator {{ $jadwalAktif ? '' : 'inactive' }}"></div>
            </div>
            <div class="time-display">
                <div id="current-time" class="current-time">00:00:00</div>
                <div id="current-date" class="current-date">Hari, 1 Januari 2024</div>
            </div>
        </div>

        <!-- Scan Limit Warning -->
        <div id="scan-limit-warning" class="scan-limit-warning">
            <i class="fas fa-exclamation-triangle warning-icon"></i>
            <p><strong>Peringatan:</strong> Scanner akan berhenti sementara karena terlalu banyak percobaan scan.</p>
            <p class="text-sm">Tunggu <span id="countdown-timer">10</span> detik untuk scan kembali.</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Scanner Section -->
            <div class="scanner-section">
                <h3 class="section-title">
                    <i class="fas fa-camera section-icon camera-blue"></i>Scanner QR Code
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
                <div class="controls-grid" style="align-items:center;">
                    <button id="btn-start-scanner" onclick="startScanner()" class="btn btn-success">
                        <i class="fas fa-play btn-icon"></i>Mulai Scan
                    </button>
                    <button id="btn-stop-scanner" onclick="stopScanner()" class="btn btn-danger hidden">
                        <i class="fas fa-stop btn-icon"></i>Stop Scan
                    </button>
                </div>

                <!-- Camera Controls -->
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <div class="camera-controls" style="justify-content:flex-start;">
                        <button onclick="switchCamera()" class="btn btn-secondary" title="Ganti kamera">
                            <i class="fas fa-sync-alt"></i>
                        </button>

                        <button id="btn-flash" onclick="toggleFlash()" class="btn btn-secondary hidden" title="Toggle flash">
                            <i class="fas fa-bolt"></i>
                        </button>

                        <select id="camera-select" onchange="changeCamera(this.value)" style="padding:.5rem; border-radius:.5rem; margin-left:0.5rem;">
                            <option value="">Pilih Kamera</option>
                        </select>
                    </div>
                    <div style="font-size:0.85rem; color:#6B7280; margin-top:0.25rem;">
                        Tips: Arahkan kamera ke QR Code, scanner akan bekerja otomatis
                    </div>
                </div>
            </div>

            <!-- Manual Input Section -->
            <div class="input-section">
                <h3 class="section-title">
                    <i class="fas fa-keyboard section-icon camera-purple"></i>Input Manual
                </h3>

                <div class="input-group">
                    <input type="text" 
                           id="manual-barcode" 
                           class="barcode-input"
                           placeholder="Ketik kode barcode di sini..."
                           autocomplete="off"
                           autocapitalize="none">

                    <button onclick="processManualBarcode()" class="btn btn-primary">
                        <i class="fas fa-paper-plane btn-icon"></i>Proses Absensi
                    </button>
                </div>
            </div>

            <!-- Result Section -->
            <div class="result-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check section-icon camera-green"></i>Hasil
                </h3>

                <div id="result-container">
                    <div class="result-placeholder">
                        <i class="fas fa-qrcode placeholder-icon"></i>
                        <p>Scan atau input kode untuk absensi</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-grid">
                    <div class="stat-card stat-success">
                        <div class="stat-number" id="stat-success">0</div>
                        <div class="stat-label">Berhasil</div>
                    </div>
                    <div class="stat-card stat-failed">
                        <div class="stat-number" id="stat-failed">0</div>
                        <div class="stat-label">Gagal</div>
                    </div>
                </div>
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
                <button class="btn-modal btn-modal-primary" onclick="closeModal('success-modal')">
                    <i class="fas fa-check btn-icon"></i>Oke
                </button>
                <button class="btn-modal btn-modal-secondary" onclick="closeModal('success-modal')">
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
                <button class="btn-modal btn-modal-primary" onclick="closeModal('already-absen-modal')">
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
    let successCount = 0;
    let failedCount = 0;
    
    // Variabel untuk membatasi scan
    let lastScannedCode = '';
    let lastScanTime = 0;
    let scanCooldown = 3000; // 3 detik cooldown antara scan
    let consecutiveFails = 0;
    let maxConsecutiveFails = 5; // Maksimal 5 gagal berturut-turut
    let isScanLimited = false;

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

    // Scan limit functions
    function enableScanLimit() {
        isScanLimited = true;
        const warning = document.getElementById('scan-limit-warning');
        warning.classList.add('active');
        
        if (isScannerActive) {
            stopScanner();
        }
        
        let countdown = 10;
        const timerElement = document.getElementById('countdown-timer');
        timerElement.textContent = countdown;
        
        const countdownInterval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                disableScanLimit();
            }
        }, 1000);
    }

    function disableScanLimit() {
        isScanLimited = false;
        const warning = document.getElementById('scan-limit-warning');
        warning.classList.remove('active');
        consecutiveFails = 0;
        
        if (!isScannerActive) {
            startScanner();
        }
    }

    function checkScanLimit() {
        consecutiveFails++;
        
        if (consecutiveFails >= maxConsecutiveFails) {
            enableScanLimit();
            return true;
        }
        return false;
    }

    // Populate camera list dropdown
    async function populateCameraList() {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return;
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            const select = document.getElementById('camera-select');
            if (!select) return;
            
            // clear existing options beyond first
            while (select.options.length > 1) select.remove(1);
            
            videoDevices.forEach((dev, idx) => {
                const opt = document.createElement('option');
                opt.value = dev.deviceId;
                opt.textContent = dev.label || (`Kamera ${idx + 1}`);
                select.appendChild(opt);
            });
        } catch (err) {
            console.warn('populateCameraList error', err);
        }
    }

    // Change camera
    async function changeCamera(deviceId) {
        currentCameraId = deviceId || null;
        if (isScannerActive) {
            await stopScanner();
            setTimeout(() => startScanner(), 500);
        }
    }

    // Start scanner dengan konfigurasi yang kompatibel
    async function startScanner() {
        if (isScanLimited) {
            console.log('Scanner sedang dibatasi karena terlalu banyak percobaan');
            return;
        }

        try {
            markUserInteraction();
            
            document.getElementById('btn-start-scanner').classList.add('hidden');
            document.getElementById('btn-stop-scanner').classList.remove('hidden');
            document.getElementById('scanner-loading').classList.remove('hidden');

            // Stop scanner sebelumnya jika ada
            if (html5QrcodeScanner) {
                await html5QrcodeScanner.clear().catch(console.error);
            }

            // Konfigurasi yang kompatibel dengan semua versi
            const config = {
                fps: 10,
                qrbox: 250,
                aspectRatio: 1.0
            };

            // Buat scanner baru dengan konfigurasi sederhana
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                config,
                false
            );

            // Render scanner
            await html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            
            isScannerActive = true;
            document.getElementById('scanner-loading').classList.add('hidden');
            vibrate(50);
            
            // Update camera list
            setTimeout(populateCameraList, 1000);
            
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
        vibrate(100);
    }

    // Switch camera menggunakan Html5Qrcode langsung
    async function switchCamera() {
        if (!isScannerActive) return;
        
        try {
            // Gunakan Html5Qrcode untuk mendapatkan daftar kamera
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
            vibrate(30);
            
        } catch (error) {
            console.error('Error switching camera:', error);
        }
    }

    // Toggle flash (sederhana)
    async function toggleFlash() {
        // Implementasi sederhana - bisa dikembangkan
        alert('Fitur flash sedang dalam pengembangan');
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

    // Process manual barcode input
    function processManualBarcode() {
        if (isScanLimited) {
            showResult('error', 'Scanner sedang dibatasi. Tunggu beberapa detik.');
            return;
        }

        markUserInteraction();
        const barcode = document.getElementById('manual-barcode').value.trim();
        if (!barcode) {
            showResult('error', 'Masukkan kode barcode terlebih dahulu');
            vibrate(200);
            return;
        }
        vibrate(50);
        document.getElementById('manual-barcode').value = '';
        processBarcode(barcode);
    }

    // Process barcode data
    function processBarcode(barcodeData) {
    showResult('loading', 'Memproses absensi...');
    
    if (!barcodeData || typeof barcodeData !== 'string') {
        handleProcessResult(false, { message: 'Data barcode tidak valid.' });
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
        
        // JANGAN throw error di sini, kembalikan data saja
        // Response 400 adalah response normal untuk kasus "sudah absen"
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
        handleProcessResult(false, { message: error.message || 'Koneksi jaringan bermasalah' });
    });
}


    // Handle process result
function handleProcessResult(success, data) {
    console.log('Handle process result:', { success, data }); // Debug
    
    if (success) {
        successCount++;
        document.getElementById('stat-success').textContent = successCount;
        showResult('success', data);
        showSuccessModal(data.data);
        vibrate([100, 50, 100]);
        consecutiveFails = 0;
    } else {
        // Cek berbagai jenis error
        if (data.message && data.message.includes('sudah absen')) {
            // Untuk kasus sudah absen, gunakan data yang dikirim server
            const absenData = data.data || extractDataFromMessage(data);
            showAlreadyAbsenModal(absenData);
            vibrate(200);
        } else {
            failedCount++;
            document.getElementById('stat-failed').textContent = failedCount;
            showResult('error', data.message || data);
            
            if (checkScanLimit()) {
                vibrate(300);
            } else {
                vibrate(200);
            }
        }
    }
}

// Helper function untuk extract data dari response
function extractDataFromMessage(data) {
    // Coba extract data dari berbagai kemungkinan struktur response
    return {
        nama: data.nama || data.data?.nama || '-',
        mata_kuliah: data.mata_kuliah || data.data?.mata_kuliah || '-',
        sesi: data.sesi || data.data?.sesi || '-',
        status: data.status || data.data?.status || 'hadir',
        waktu: data.waktu || data.data?.waktu || '-',
        tanggal: data.tanggal || data.data?.tanggal || '-'
    };
}
    // Show success modal
    function showSuccessModal(data) {
        const modalContent = document.getElementById('success-modal-content');
        modalContent.innerHTML = `
            <div class="info-item">
                <span class="info-label">Nama:</span>
                <span class="info-value" style="color: #065F46; font-weight: 700;">${data.nama || '-'}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Mata Kuliah:</span>
                <span class="info-value">${data.mata_kuliah || '-'}</span>
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
    
    // Validasi dan sanitasi data
    const safeData = {
        nama: data?.nama || '-',
        mata_kuliah: data?.mata_kuliah || '-',
        sesi: data?.sesi || '-',
        status: data?.status || 'hadir',
        waktu: data?.waktu || '-',
        tanggal: data?.tanggal || '-'
    };

    modalContent.innerHTML = `
        <div class="info-item">
            <span class="info-label">Nama:</span>
            <span class="info-value">${safeData.nama}</span><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Scanner Absensi - Absensi Barcode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Responsive CSS untuk Web dan Mobile -->
    <style>
        /* Reset dan Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background: linear-gradient(135deg, #3B82F6, #8B5CF6); 
            min-height: 100vh;
            padding: 0;
        }
        .container { 
            max-width: 100%;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header { 
            text-align: center; 
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }
        .logo-container {
            background: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .logo-icon {
            color: #2563EB;
            font-size: 2rem;
        }
        .title {
            font-size: 1.75rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.25rem;
        }
        .subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        .kegiatan-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .kegiatan-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .kegiatan-text { flex: 1; }
        .kegiatan-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .kegiatan-desc {
            font-size: 0.85rem;
            color: #6B7280;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .kegiatan-time {
            font-size: 0.9rem;
            color: #2563EB;
            font-weight: 600;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #10B981;
            margin-left: 0.75rem;
            flex-shrink: 0;
            margin-top: 0.25rem;
        }
        .status-indicator.inactive { background: #EF4444; }
        .time-display { text-align: right; padding-top: 0.75rem; border-top: 1px solid #F3F4F6; }
        .current-time {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 0.25rem;
        }
        .current-date { font-size: 0.85rem; color: #6B7280; }

        .main-content { display: flex; flex-direction: column; gap: 1.25rem; flex: 1; }

        .scanner-section {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .section-icon { margin-right: 0.5rem; font-size: 1.1rem; }
        .camera-blue { color: #2563EB; }
        .camera-purple { color: #8B5CF6; }
        .camera-green { color: #059669; }

        .scanner-container { 
            position: relative; 
            width: 100%; 
            margin-bottom: 1rem; 
            border-radius: 0.75rem;
            overflow: hidden;
            background: #000;
            min-height: 300px;
        }
        #reader { 
            width: 100% !important; 
            height: 100% !important;
        }
        .scanner-overlay { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            pointer-events: none; 
            z-index: 10;
        }
        .scanning-line { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 3px; 
            background: linear-gradient(90deg, transparent, #3B82F6, transparent); 
            animation: scan 2s infinite; 
        }
        @keyframes scan { 
            0% { transform: translateY(0); } 
            50% { transform: translateY(100%); } 
            100% { transform: translateY(0); } 
        }
        .scanner-frame { 
            position: absolute; 
            top: 10%; 
            left: 10%; 
            width: 80%; 
            height: 80%; 
            border: 2px solid rgba(59, 130, 246, 0.4); 
            border-radius: 0.5rem; 
        }

        .controls-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
        .camera-controls { display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn { padding: 0.875rem 1rem; border-radius: 0.75rem; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; min-height: 48px; }
        .btn-icon { margin-right: 0.5rem; font-size: 0.9rem; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #DC2626; color: white; }
        .btn-danger:hover { background: #B91C1C; }
        .btn-secondary { background: #6B7280; color: white; padding: 0.75rem; }
        .btn-secondary:hover { background: #4B5563; }
        .btn-primary { background: #2563EB; color: white; }
        .btn-primary:hover { background: #1D4ED8; }
        .hidden { display: none !important; }

        .input-section { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .input-group { display: flex; flex-direction: column; gap: 0.75rem; }
        .barcode-input { width: 100%; padding: 1rem; border: 2px solid #E5E7EB; border-radius: 0.75rem; font-family: monospace; font-size: 1.1rem; text-align: center; transition: border-color 0.2s; }
        .barcode-input:focus { outline: none; border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        .result-section { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        #result-container { min-height: 140px; display: flex; align-items: center; justify-content: center; }
        .result-placeholder { text-align: center; color: #9CA3AF; }
        .placeholder-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }

        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem; }
        .stat-card { padding: 1rem; border-radius: 0.75rem; text-align: center; }
        .stat-success { background: #ECFDF5; border: 1px solid #A7F3D0; }
        .stat-failed { background: #FEF2F2; border: 1px solid #FECACA; }
        .stat-number { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.25rem; }
        .stat-success .stat-number { color: #059669; }
        .stat-failed .stat-number { color: #DC2626; }
        .stat-label { font-size: 0.8rem; font-weight: 600; }
        .stat-success .stat-label { color: #065F46; }
        .stat-failed .stat-label { color: #991B1B; }

        .navigation { text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); }
        .nav-link { color: white; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.5rem; transition: background 0.2s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        .nav-icon { margin-right: 0.5rem; }

        .result-success { background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 0.75rem; padding: 1.25rem; text-align: center; }
        .result-error { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 0.75rem; padding: 1.25rem; text-align: center; }
        .result-icon { width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .success-icon { background: #D1FAE5; color: #059669; }
        .error-icon { background: #FEE2E2; color: #DC2626; }
        .result-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
        .result-success .result-title { color: #065F46; }
        .result-error .result-title { color: #991B1B; }
        .result-details { display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; }
        .detail-label { color: #6B7280; }
        .detail-value { font-weight: 500; }
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-hadir { background: #D1FAE5; color: #065F46; }
        .status-terlambat { background: #FEF3C7; color: #92400E; }

        .scanner-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.8);
            color: white;
            z-index: 5;
        }
        .loading-spinner { width: 2.5rem; height: 2.5rem; border: 3px solid transparent; border-top: 3px solid white; border-radius: 50%; margin: 0 auto 0.75rem; animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }

        .modal-icon.success {
            background: #D1FAE5;
            color: #059669;
        }

        .modal-icon.warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            color: #6B7280;
            font-size: 1rem;
        }

        .modal-body {
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6B7280;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .modal-footer {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-modal {
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 120px;
        }

        .btn-modal-primary {
            background: #2563EB;
            color: white;
        }

        .btn-modal-primary:hover {
            background: #1D4ED8;
        }

        .btn-modal-secondary {
            background: #6B7280;
            color: white;
        }

        .btn-modal-secondary:hover {
            background: #4B5563;
        }

        /* Scan Limit Warning */
        .scan-limit-warning {
            background: #FEF3C7;
            border: 1px solid #F59E0B;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            display: none;
        }

        .scan-limit-warning.active {
            display: block;
        }

        .warning-icon {
            color: #D97706;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 640px) {
            .container { max-width: 640px; padding: 1.5rem; }
            .controls-grid { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (min-width: 768px) {
            .container { max-width: 768px; }
            .main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            .scanner-section { grid-column: 1 / -1; }
            .modal-content { padding: 2.5rem; }
        }
        @media (min-width: 1024px) {
            .container { max-width: 1024px; }
            .main-content { grid-template-columns: 2fr 1fr; }
            .scanner-section { grid-column: 1; }
            .input-section { grid-column: 2; grid-row: 1; }
            .result-section { grid-column: 2; grid-row: 2; }
        }
        @media (max-height: 700px) {
            .container { padding: 1rem; }
            .header { margin-bottom: 1rem; }
            .kegiatan-card { margin-bottom: 1rem; padding: 1rem; }
            .scanner-section, .input-section, .result-section { padding: 1rem; }
            .modal-content { padding: 1.5rem; }
        }
        @media (max-width: 480px) {
            .modal-content { margin: 1rem; padding: 1.5rem; }
            .modal-footer { flex-direction: column; }
            .btn-modal { width: 100%; }
        }
        #qr-shaded-region {
    border-width: 0px !important;
    border: none !important;
}

/* Custom scanner frame yang lebih baik */
.scanner-frame-custom {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 250px;
    height: 250px;
    border: 3px solid #3B82F6;
    border-radius: 12px;
    box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.4);
    z-index: 15;
    pointer-events: none;
}

/* Corner indicators */
.scanner-frame-custom::before,
.scanner-frame-custom::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid #3B82F6;
}

.scanner-frame-custom::before {
    top: -3px;
    left: -3px;
    border-right: none;
    border-bottom: none;
    border-top-left-radius: 8px;
}

.scanner-frame-custom::after {
    top: -3px;
    right: -3px;
    border-left: none;
    border-bottom: none;
    border-top-right-radius: 8px;
}

.scanner-frame-custom .corner-bottom-left,
.scanner-frame-custom .corner-bottom-right {
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid #3B82F6;
    bottom: -3px;
}

.scanner-frame-custom .corner-bottom-left {
    left: -3px;
    border-right: none;
    border-top: none;
    border-bottom-left-radius: 8px;
}

.scanner-frame-custom .corner-bottom-right {
    right: -3px;
    border-left: none;
    border-top: none;
    border-bottom-right-radius: 8px;
}
    </style>

    <!-- Scanner Library yang lebih stabil -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <i class="fas fa-qrcode logo-icon"></i>
            </div>
            <h1 class="title">SCANNER ABSENSI</h1>
            <p class="subtitle">Scan QR Code untuk absensi (Laptop & Mobile)</p>
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
                <div class="status-indicator {{ $kegiatanAktif ? '' : 'inactive' }}"></div>
            </div>
            <div class="time-display">
                <div id="current-time" class="current-time">00:00:00</div>
                <div id="current-date" class="current-date">Hari, 1 Januari 2024</div>
            </div>
        </div>

        <!-- Scan Limit Warning -->
        <div id="scan-limit-warning" class="scan-limit-warning">
            <i class="fas fa-exclamation-triangle warning-icon"></i>
            <p><strong>Peringatan:</strong> Scanner akan berhenti sementara karena terlalu banyak percobaan scan.</p>
            <p class="text-sm">Tunggu <span id="countdown-timer">10</span> detik untuk scan kembali.</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Scanner Section -->
            <div class="scanner-section">
                <h3 class="section-title">
                    <i class="fas fa-camera section-icon camera-blue"></i>Scanner QR Code
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
                <div class="controls-grid" style="align-items:center;">
                    <button id="btn-start-scanner" onclick="startScanner()" class="btn btn-success">
                        <i class="fas fa-play btn-icon"></i>Mulai Scan
                    </button>
                    <button id="btn-stop-scanner" onclick="stopScanner()" class="btn btn-danger hidden">
                        <i class="fas fa-stop btn-icon"></i>Stop Scan
                    </button>
                </div>

                <!-- Camera Controls -->
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <div class="camera-controls" style="justify-content:flex-start;">
                        <button onclick="switchCamera()" class="btn btn-secondary" title="Ganti kamera">
                            <i class="fas fa-sync-alt"></i>
                        </button>

                        <button id="btn-flash" onclick="toggleFlash()" class="btn btn-secondary hidden" title="Toggle flash">
                            <i class="fas fa-bolt"></i>
                        </button>

                        <select id="camera-select" onchange="changeCamera(this.value)" style="padding:.5rem; border-radius:.5rem; margin-left:0.5rem;">
                            <option value="">Pilih Kamera</option>
                        </select>
                    </div>
                    <div style="font-size:0.85rem; color:#6B7280; margin-top:0.25rem;">
                        Tips: Arahkan kamera ke QR Code, scanner akan bekerja otomatis
                    </div>
                </div>
            </div>

            <!-- Manual Input Section -->
            <div class="input-section">
                <h3 class="section-title">
                    <i class="fas fa-keyboard section-icon camera-purple"></i>Input Manual
                </h3>

                <div class="input-group">
                    <input type="text" 
                           id="manual-barcode" 
                           class="barcode-input"
                           placeholder="Ketik kode barcode di sini..."
                           autocomplete="off"
                           autocapitalize="none">

                    <button onclick="processManualBarcode()" class="btn btn-primary">
                        <i class="fas fa-paper-plane btn-icon"></i>Proses Absensi
                    </button>
                </div>
            </div>

            <!-- Result Section -->
            <div class="result-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check section-icon camera-green"></i>Hasil
                </h3>

                <div id="result-container">
                    <div class="result-placeholder">
                        <i class="fas fa-qrcode placeholder-icon"></i>
                        <p>Scan atau input kode untuk absensi</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-grid">
                    <div class="stat-card stat-success">
                        <div class="stat-number" id="stat-success">0</div>
                        <div class="stat-label">Berhasil</div>
                    </div>
                    <div class="stat-card stat-failed">
                        <div class="stat-number" id="stat-failed">0</div>
                        <div class="stat-label">Gagal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <a href="{{ url('/dashboard') }}" class="nav-link">
                <i class="fas fa-arrow-left nav-icon"></i>Kembali ke dashboard
            </a>
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
                <button class="btn-modal btn-modal-primary" onclick="closeModal('success-modal')">
                    <i class="fas fa-check btn-icon"></i>Oke
                </button>
                <button class="btn-modal btn-modal-secondary" onclick="closeModal('success-modal')">
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
                <button class="btn-modal btn-modal-primary" onclick="closeModal('already-absen-modal')">
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
    let successCount = 0;
    let failedCount = 0;
    
    // Variabel untuk membatasi scan
    let lastScannedCode = '';
    let lastScanTime = 0;
    let scanCooldown = 3000; // 3 detik cooldown antara scan
    let consecutiveFails = 0;
    let maxConsecutiveFails = 5; // Maksimal 5 gagal berturut-turut
    let isScanLimited = false;

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

    // Scan limit functions
    function enableScanLimit() {
        isScanLimited = true;
        const warning = document.getElementById('scan-limit-warning');
        warning.classList.add('active');
        
        if (isScannerActive) {
            stopScanner();
        }
        
        let countdown = 10;
        const timerElement = document.getElementById('countdown-timer');
        timerElement.textContent = countdown;
        
        const countdownInterval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                disableScanLimit();
            }
        }, 1000);
    }

    function disableScanLimit() {
        isScanLimited = false;
        const warning = document.getElementById('scan-limit-warning');
        warning.classList.remove('active');
        consecutiveFails = 0;
        
        if (!isScannerActive) {
            startScanner();
        }
    }

    function checkScanLimit() {
        consecutiveFails++;
        
        if (consecutiveFails >= maxConsecutiveFails) {
            enableScanLimit();
            return true;
        }
        return false;
    }

    // Populate camera list dropdown
    async function populateCameraList() {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return;
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            const select = document.getElementById('camera-select');
            if (!select) return;
            
            // clear existing options beyond first
            while (select.options.length > 1) select.remove(1);
            
            videoDevices.forEach((dev, idx) => {
                const opt = document.createElement('option');
                opt.value = dev.deviceId;
                opt.textContent = dev.label || (`Kamera ${idx + 1}`);
                select.appendChild(opt);
            });
        } catch (err) {
            console.warn('populateCameraList error', err);
        }
    }

    // Change camera
    async function changeCamera(deviceId) {
        currentCameraId = deviceId || null;
        if (isScannerActive) {
            await stopScanner();
            setTimeout(() => startScanner(), 500);
        }
    }

    // Start scanner dengan konfigurasi yang kompatibel
    async function startScanner() {
        if (isScanLimited) {
            console.log('Scanner sedang dibatasi karena terlalu banyak percobaan');
            return;
        }

        try {
            markUserInteraction();
            
            document.getElementById('btn-start-scanner').classList.add('hidden');
            document.getElementById('btn-stop-scanner').classList.remove('hidden');
            document.getElementById('scanner-loading').classList.remove('hidden');

            // Stop scanner sebelumnya jika ada
            if (html5QrcodeScanner) {
                await html5QrcodeScanner.clear().catch(console.error);
            }

            // Konfigurasi yang kompatibel dengan semua versi
            const config = {
                fps: 10,
                qrbox: 250,
                aspectRatio: 1.0
            };

            // Buat scanner baru dengan konfigurasi sederhana
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                config,
                false
            );

            // Render scanner
            await html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            
            isScannerActive = true;
            document.getElementById('scanner-loading').classList.add('hidden');
            vibrate(50);
            
            // Update camera list
            setTimeout(populateCameraList, 1000);
            
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
        vibrate(100);
    }

    // Switch camera menggunakan Html5Qrcode langsung
    async function switchCamera() {
        if (!isScannerActive) return;
        
        try {
            // Gunakan Html5Qrcode untuk mendapatkan daftar kamera
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
            vibrate(30);
            
        } catch (error) {
            console.error('Error switching camera:', error);
        }
    }

    // Toggle flash (sederhana)
    async function toggleFlash() {
        // Implementasi sederhana - bisa dikembangkan
        alert('Fitur flash sedang dalam pengembangan');
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

    // Process manual barcode input
    function processManualBarcode() {
        if (isScanLimited) {
            showResult('error', 'Scanner sedang dibatasi. Tunggu beberapa detik.');
            return;
        }

        markUserInteraction();
        const barcode = document.getElementById('manual-barcode').value.trim();
        if (!barcode) {
            showResult('error', 'Masukkan kode barcode terlebih dahulu');
            vibrate(200);
            return;
        }
        vibrate(50);
        document.getElementById('manual-barcode').value = '';
        processBarcode(barcode);
    }

    // Process barcode data
    function processBarcode(barcodeData) {
    showResult('loading', 'Memproses absensi...');
    
    if (!barcodeData || typeof barcodeData !== 'string') {
        handleProcessResult(false, { message: 'Data barcode tidak valid.' });
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
        
        // JANGAN throw error di sini, kembalikan data saja
        // Response 400 adalah response normal untuk kasus "sudah absen"
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
        handleProcessResult(false, { message: error.message || 'Koneksi jaringan bermasalah' });
    });
}


    // Handle process result
function handleProcessResult(success, data) {
    console.log('Handle process result:', { success, data }); // Debug
    
    if (success) {
        successCount++;
        document.getElementById('stat-success').textContent = successCount;
        showResult('success', data);
        showSuccessModal(data.data);
        vibrate([100, 50, 100]);
        consecutiveFails = 0;
    } else {
        // Cek berbagai jenis error
        if (data.message && data.message.includes('sudah absen')) {
            // Untuk kasus sudah absen, gunakan data yang dikirim server
            const absenData = data.data || extractDataFromMessage(data);
            showAlreadyAbsenModal(absenData);
            vibrate(200);
        } else {
            failedCount++;
            document.getElementById('stat-failed').textContent = failedCount;
            showResult('error', data.message || data);
            
            if (checkScanLimit()) {
                vibrate(300);
            } else {
                vibrate(200);
            }
        }
    }
}

// Helper function untuk extract data dari response
function extractDataFromMessage(data) {
    // Coba extract data dari berbagai kemungkinan struktur response
    return {
        nama: data.nama || data.data?.nama || '-',
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
        modalContent.innerHTML = `
            <div class="info-item">
                <span class="info-label">Nama:</span>
                <span class="info-value" style="color: #065F46; font-weight: 700;">${data.nama || '-'}</span>
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
    
    // Validasi dan sanitasi data
    const safeData = {
        nama: data?.nama || '-',
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

    // Show result (untuk tampilan kecil di result section)
    function showResult(type, data) {
        const container = document.getElementById('result-container');
        if (!container) return;

        if (type === 'loading') {
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="loading-spinner"></div>
                    <p style="color: #6B7280; margin-top: 0.5rem;">${data}</p>
                </div>`;
            return;
        }

        if (type === 'success') {
            const d = data.data || {};
            container.innerHTML = `
                <div class="result-success">
                    <div class="result-icon success-icon">
                        <i class="fas fa-check" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="result-title">Absensi Berhasil!</h4>
                    <div class="result-details">
                        <div class="detail-row">
                            <span class="detail-label">Nama:</span>
                            <span class="detail-value" style="font-weight: 600; color: #065F46;">${d.nama || '-'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Program:</span>
                            <span class="detail-value">${d.program || '-'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="status-badge ${d.status === 'hadir' ? 'status-hadir' : 'status-terlambat'}">${d.status || '-'}</span>
                        </div>
                    </div>
                </div>`;
        } else {
            const message = (typeof data === 'string') ? data : (data.message || 'Terjadi kesalahan');
            container.innerHTML = `
                <div class="result-error">
                    <div class="result-icon error-icon">
                        <i class="fas fa-times" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="result-title">Absensi Gagal</h4>
                    <p style="color: #DC2626;">${message}</p>
                </div>`;
        }

        // Reset result setelah 5 detik (kecuali untuk modal)
        if (type !== 'success' || !data.message?.includes('sudah absen')) {
            setTimeout(() => {
                container.innerHTML = `
                    <div class="result-placeholder">
                        <i class="fas fa-qrcode placeholder-icon"></i>
                        <p>Scan atau input kode untuk absensi</p>
                    </div>`;
            }, 5000);
        }
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

        showResult('error', errorMessage);
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
        // Mark user interaction
        document.addEventListener('click', markUserInteraction);
        document.addEventListener('touchstart', markUserInteraction);

        // Auto-start scanner
        setTimeout(() => {
            if (!document.hidden && !isScanLimited) {
                startScanner();
            }
        }, 1000);

        // Manual input handler
        const manualInput = document.getElementById('manual-barcode');
        if (manualInput) {
            manualInput.addEventListener('keypress', function(e) { 
                if (e.key === 'Enter') processManualBarcode(); 
            });
        }

        // Handle page visibility
        document.addEventListener('visibilitychange', function() { 
            if (document.hidden && isScannerActive) {
                stopScanner();
            } else if (!document.hidden && !isScannerActive && !isScanLimited) {
                // Restart scanner ketika tab aktif kembali
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
        </div>
        <div class="info-item">
            <span class="info-label">Program:</span>
            <span class="info-value">${safeData.mata_kuliah}</span>
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

    // Show result (untuk tampilan kecil di result section)
    function showResult(type, data) {
        const container = document.getElementById('result-container');
        if (!container) return;

        if (type === 'loading') {
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="loading-spinner"></div>
                    <p style="color: #6B7280; margin-top: 0.5rem;">${data}</p>
                </div>`;
            return;
        }

        if (type === 'success') {
            const d = data.data || {};
            container.innerHTML = `
                <div class="result-success">
                    <div class="result-icon success-icon">
                        <i class="fas fa-check" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="result-title">Absensi Berhasil!</h4>
                    <div class="result-details">
                        <div class="detail-row">
                            <span class="detail-label">Nama:</span>
                            <span class="detail-value" style="font-weight: 600; color: #065F46;">${d.nama || '-'}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="status-badge ${d.status === 'hadir' ? 'status-hadir' : 'status-terlambat'}">${d.status || '-'}</span>
                        </div>
                    </div>
                </div>`;
        } else {
            const message = (typeof data === 'string') ? data : (data.message || 'Terjadi kesalahan');
            container.innerHTML = `
                <div class="result-error">
                    <div class="result-icon error-icon">
                        <i class="fas fa-times" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="result-title">Absensi Gagal</h4>
                    <p style="color: #DC2626;">${message}</p>
                </div>`;
        }

        // Reset result setelah 5 detik (kecuali untuk modal)
        if (type !== 'success' || !data.message?.includes('sudah absen')) {
            setTimeout(() => {
                container.innerHTML = `
                    <div class="result-placeholder">
                        <i class="fas fa-qrcode placeholder-icon"></i>
                        <p>Scan atau input kode untuk absensi</p>
                    </div>`;
            }, 5000);
        }
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

        showResult('error', errorMessage);
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
        // Mark user interaction
        document.addEventListener('click', markUserInteraction);
        document.addEventListener('touchstart', markUserInteraction);

        // Auto-start scanner
        setTimeout(() => {
            if (!document.hidden && !isScanLimited) {
                startScanner();
            }
        }, 1000);

        // Manual input handler
        const manualInput = document.getElementById('manual-barcode');
        if (manualInput) {
            manualInput.addEventListener('keypress', function(e) { 
                if (e.key === 'Enter') processManualBarcode(); 
            });
        }

        // Handle page visibility
        document.addEventListener('visibilitychange', function() { 
            if (document.hidden && isScannerActive) {
                stopScanner();
            } else if (!document.hidden && !isScannerActive && !isScanLimited) {
                // Restart scanner ketika tab aktif kembali
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