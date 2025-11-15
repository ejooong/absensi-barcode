<!-- resources/views/auth/share.blade.php -->
@extends('layouts.app')

@section('title', 'Share Scanner')

@section('content')

<!-- Animated Background -->
<div class="animated-bg"></div>
<div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
</div>

<div class="min-h-screen flex items-center justify-center relative z-10">
    <div class="max-w-2xl w-full mx-4">
        <!-- Share Scanner Section -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-22 h-22 bg-white-100 rounded-full flex items-center justify-center mb-4">
                   <img src="{{ asset('images/nasdem.png') }}" 
                     alt="ABSENSI BARCODE" 
                     style="height: 100px; width: 100px;"
                     class="mr-1 object-contain">
                   <img src="{{ asset('images/abn.png') }}" 
                     alt="ABSENSI BARCODE" 
                     style="height: 120px; width: 120px;"
                     class="mr-1 object-contain">
                     

                </div>
              
                <p class="text-gray-600 mt-2">Bagikan ke Peserta atau Fasilitator </p>
            </div>

            <!-- Scanner Info -->
            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-green-700 text-sm text-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Peserta dapat scan QR code tanpa perlu login
                </p>
            </div>

            <!-- Share Options -->
            <div class="space-y-4">
                <!-- Scanner URL -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-link mr-2"></i>Link Scanner
                    </label>
                    <div class="flex">
                        <input 
                            type="text" 
                            id="scanner-url" 
                            value="{{ route('scanner.public') }}" 
                            readonly
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 text-sm"
                        >
                        <button 
                            onclick="copyScannerUrl()"
                            class="bg-blue-600 text-white px-4 py-3 rounded-r-lg hover:bg-blue-700 transition-colors"
                        >
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- WhatsApp -->
                    <button 
                        onclick="shareViaWhatsApp()"
                        class="bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-all duration-200 flex items-center justify-center"
                    >
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp
                    </button>

                    <!-- QR Code -->
                    <button 
                        onclick="showQRCode()"
                        class="bg-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-purple-700 transition-all duration-200 flex items-center justify-center"
                    >
                        <i class="fas fa-qrcode mr-2"></i>
                        QR Code
                    </button>
                </div>

                <!-- Quick Message -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-share-alt mr-2"></i>Pesan Cepat
                    </label>
                    <div class="flex space-x-2">
                        <button 
                            onclick="shareWithMessage('default')"
                            class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-lg text-sm hover:bg-blue-200 transition-colors"
                        >
                            📱 Scan absensi di sini
                        </button>
                        <button 
                            onclick="shareWithMessage('urgent')"
                            class="flex-1 bg-orange-100 text-orange-700 py-2 px-3 rounded-lg text-sm hover:bg-orange-200 transition-colors"
                        >
                            ⏰ Segera absen!
                        </button>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Cara Berbagi:
                </h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Klik tombol media sosial untuk berbagi langsung</li>
                    <li>• Salin link untuk dibagikan manual</li>
                    <li>• Gunakan QR code untuk display di kelas</li>
                    <li>• Peserta tidak perlu login untuk scan</li>
                </ul>
            </div>

            <!-- Link ke Login Page -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Petugas? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white text-sm drop-shadow-lg">
                &copy; 2025 ABN Absensi Barcode System
            </p>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qr-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-8 max-w-sm mx-4">
        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4">QR Code Scanner</h3>
            <div id="qrcode" class="mb-4 mx-auto flex justify-center items-center"></div>
            <p class="text-sm text-gray-600 mb-4">Scan QR code ini untuk mengakses scanner absensi</p>
            <button 
                onclick="closeQRModal()"
                class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Animated Background */
    .animated-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        background: linear-gradient(-45deg, 
            rgba(24, 35, 77, 1) 0%, 
            rgba(15, 51, 118, 1) 25%, 
            rgba(255, 255, 255, 0.8) 50%, 
            rgba(15, 51, 118, 0.9) 75%, 
            rgba(24, 35, 77, 1) 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }

    .animated-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(16, 185, 129, 0.2) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }

    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .shape {
        position: absolute;
        opacity: 0.1;
        animation: float 25s infinite linear;
    }

    .shape-1 {
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, #3B82F6, #8B5CF6);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 150px;
        height: 150px;
        background: linear-gradient(45deg, #10B981, #059669);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        top: 60%;
        right: 10%;
        animation-delay: -5s;
    }

    .shape-3 {
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, #F59E0B, #D97706);
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        bottom: 20%;
        left: 20%;
        animation-delay: -10s;
    }

    .shape-4 {
        width: 120px;
        height: 120px;
        background: linear-gradient(45deg, #EF4444, #DC2626);
        border-radius: 40% 60% 60% 40% / 40% 40% 60% 60%;
        top: 30%;
        right: 30%;
        animation-delay: -15s;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(120deg); }
        66% { transform: translateY(20px) rotate(240deg); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.1); opacity: 0.15; }
    }

    .shape {
        animation: float 25s infinite linear, pulse 8s infinite ease-in-out;
    }

    /* Glass morphism effect for cards */
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }

    .bg-white\/95 {
        background-color: rgba(255, 255, 255, 0.95);
    }

    .border-white\/20 {
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Ensure content is above background */
    .relative.z-10 {
        position: relative;
        z-index: 10;
    }

    /* Text shadow for better readability */
    .drop-shadow-lg {
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const scannerUrl = "{{ route('scanner.public') }}";
    
    // Copy scanner URL
    function copyScannerUrl() {
        navigator.clipboard.writeText(scannerUrl).then(() => {
            showToast('Link berhasil disalin!', 'success');
        }).catch(() => {
            // Fallback untuk browser lama
            const input = document.getElementById('scanner-url');
            input.select();
            document.execCommand('copy');
            showToast('Link berhasil disalin!', 'success');
        });
    }
    
    // Share via WhatsApp
    function shareViaWhatsApp() {
        const message = `📱 Gunakan scanner absensi berikut:\n${scannerUrl}\n\n*Absensi Barcode System*`;
        const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');
    }
    

    // Share with custom message
    function shareWithMessage(type) {
        const messages = {
            default: `📱 *SCANNER ABSENSI*\n\nGunakan link berikut untuk absensi:\n${scannerUrl}\n\n_*Absensi Barcode System*_`,
            urgent: `🚨 *SEGERA ABSEN!*\n\nWaktu absensi hampir habis! Gunakan link:\n${scannerUrl}\n\n_*Segera lakukan absensi!*_`
        };
        
        const message = messages[type] || messages.default;
        const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');
    }
    
    // Show QR Code
    function showQRCode() {
        const modal = document.getElementById('qr-modal');
        const qrcodeElement = document.getElementById('qrcode');
        
        // Clear previous QR code
        qrcodeElement.innerHTML = '';
        
        try {
            // Menggunakan qrcodejs library
            new QRCode(qrcodeElement, {
                text: scannerUrl,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        } catch (error) {
            console.error('QR Code error:', error);
            showFallbackQR(qrcodeElement);
        }
        
        modal.classList.remove('hidden');
    }
    
    // Close QR Modal
    function closeQRModal() {
        document.getElementById('qr-modal').classList.add('hidden');
    }
    
    // Toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-blue-500'
        } z-50 transform transition-transform duration-300 translate-x-full`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Animate out and remove
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Close modal when clicking outside
    document.getElementById('qr-modal').addEventListener('click', (e) => {
        if (e.target.id === 'qr-modal') {
            closeQRModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQRModal();
        }
    });
</script>
@endsection