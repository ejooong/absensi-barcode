<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login Petugas')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="max-w-4xl w-full mx-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Share Scanner Section -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-qrcode text-green-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">SCANNER ABSENSI</h1>
                    <p class="text-gray-600 mt-2">Bagikan ke Peserta</p>
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
                                 value="{{ route('scanner') }}" 
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

                        <!-- Telegram -->
                        <button 
                            onclick="shareViaTelegram()"
                            class="bg-blue-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-600 transition-all duration-200 flex items-center justify-center"
                        >
                            <i class="fab fa-telegram mr-2"></i>
                            Telegram
                        </button>

                        <!-- Copy Link -->
                        <button 
                            onclick="copyScannerUrl()"
                            class="bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-200 flex items-center justify-center"
                        >
                            <i class="fas fa-link mr-2"></i>
                            Copy Link
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
            </div>

            <!-- Login Section -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">LOGIN PETUGAS</h1>
                    <p class="text-gray-600 mt-2">Akses panel admin</p>
                </div>

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Username Field -->
                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('username') border-red-500 @enderror"
                            placeholder="Masukkan username"
                            required
                            autofocus
                        >
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password"
                            required
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600 text-sm">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ $errors->first() }}
                            </p>
                        </div>
                    @endif

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:-translate-y-0.5"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        LOGIN PETUGAS
                    </button>
                </form>

                <!-- Demo Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700 text-center">
                        <strong>Demo Login:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>password123</code>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white text-sm">
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
            <div id="qrcode" class="mb-4 mx-auto"></div>
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
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    const scannerUrl = "{{ route('scanner') }}";    
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
    
    // Share via Telegram
    function shareViaTelegram() {
        const message = `📱 Gunakan scanner absensi berikut:\n${scannerUrl}\n\nAbsensi Barcode System`;
        const url = `https://t.me/share/url?url=${encodeURIComponent(scannerUrl)}&text=${encodeURIComponent(message)}`;
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
        
        // Generate new QR code
        QRCode.toCanvas(qrcodeElement, scannerUrl, {
            width: 200,
            height: 200,
            margin: 1
        }, (error) => {
            if (error) {
                console.error('QR Code error:', error);
                qrcodeElement.innerHTML = '<p class="text-red-500">Gagal generate QR Code</p>';
            }
        });
        
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