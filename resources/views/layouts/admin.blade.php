<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Absensi Barcode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header Desktop -->
    <nav class="bg-white shadow-lg hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Desktop -->
                <div class="flex items-center">
                    <div class="logo-container-desktop relative group">
                        <img src="{{ asset('images/abn.png') }}" 
                             alt="ABSENSI BARCODE" 
                             class="h-16 w-16 md:h-20 md:w-20 mr-3 object-contain transition-all duration-500 
                                    logo-image hover:scale-110 hover:rotate-3 
                                    filter brightness-100 hover:brightness-110 
                                    drop-shadow-lg hover:drop-shadow-xl rounded-lg">
                        
                        <!-- Glow effect -->
                        <div class="absolute inset-0 rounded-lg bg-blue-500 opacity-0 group-hover:opacity-10 
                                    blur-md transition-opacity duration-500 -z-10 logo-glow"></div>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-lg md:text-xl font-bold text-gray-800 tracking-tight 
                                     transition-colors duration-300 group-hover:text-blue-600">
                            ABSENSI BARCODE
                        </span>
                        <span class="text-xs text-gray-500 mt-0.5 opacity-0 group-hover:opacity-100 
                                     transition-opacity duration-500">
                            Sistem Presensi Digital
                        </span>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>

                    <!-- Data Management Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-database mr-1"></i>Kelola Data
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-200">
                            <!-- Peserta Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                <i class="fas fa-users mr-1"></i>Peserta
                            </div>
                            <a href="{{ route('peserta.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Peserta
                            </a>
                            <a href="{{ route('peserta.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-user-plus mr-2 text-green-500"></i>
                                Tambah Peserta
                            </a>

                            <!-- Program Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-book mr-1"></i>Program
                            </div>
                            <a href="{{ route('program.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Program
                            </a>
                            <a href="{{ route('program.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                                Tambah Program
                            </a>

                            <!-- Kegiatan Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i>Kegiatan
                            </div>
                            <a href="{{ route('kegiatan.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Kegiatan
                            </a>
                            <a href="{{ route('kegiatan.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                                Tambah Kegiatan
                            </a>

                            <!-- Export/Import Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-file-export mr-1"></i>Data Transfer
                            </div>
                            <a href="{{ route('export.form') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-download mr-2 text-blue-500"></i>
                                Export/Import Data
                            </a>
                            <a href="{{ route('export.peserta.template') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-file-excel mr-2 text-green-500"></i>
                                Template Import
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('absensi.riwayat') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-history mr-1"></i>Riwayat
                    </a>

                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center text-sm text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-user-circle mr-1"></i>
                            {{ Auth::guard('petugas')->user()->username }}
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200"></i>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-200">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('petugas')->user()->username }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Header Mobile -->
    <nav class="bg-white shadow-lg md:hidden">
        <div class="px-4">
            <div class="flex justify-center items-center h-16 relative">
                <!-- Logo Mobile dengan efek -->
                <div class="logo-mobile-container relative group">
                    <div class="relative">
                        <!-- Logo Image -->
                        <img src="{{ asset('images/abn.png') }}" 
                             alt="ABSENSI BARCODE" 
                             class="h-12 w-12 object-contain relative z-10 transition-all duration-300 
                                    logo-mobile-image group-hover:scale-110 group-hover:brightness-125 
                                    drop-shadow-lg rounded-lg">
                        
                        <!-- Efek cahaya kilat - hanya muncul saat hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent 
                                    opacity-0 group-hover:opacity-40 rounded-lg -z-5 
                                    group-hover:animate-lightning-flash"></div>
                        
                        <!-- Outer glow effect -->
                        <div class="absolute -inset-2 bg-blue-400 opacity-0 group-hover:opacity-30 
                                    blur-md transition-all duration-500 rounded-lg -z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bottom Navigation Mobile -->
    <div class="fixed z-40 w-full h-16 bg-white border-t border-gray-200 bottom-0 left-0 shadow-lg md:hidden">
        <div class="grid h-full grid-cols-5 relative">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex flex-col items-center justify-center px-2 hover:bg-gray-50 group transition-colors duration-200">
                <i class="fas fa-tachometer-alt text-gray-600 group-hover:text-blue-600 text-sm mb-1"></i>
                <span class="text-xs text-gray-600 group-hover:text-blue-600">Dashboard</span>
            </a>
            
            <button id="mobile-data-toggle" 
                    class="inline-flex flex-col items-center justify-center px-2 hover:bg-gray-50 group transition-colors duration-200">
                <i class="fas fa-database text-gray-600 group-hover:text-blue-600 text-sm mb-1"></i>
                <span class="text-xs text-gray-600 group-hover:text-blue-600">Data</span>
            </button>
            
            <div class="flex items-center justify-center">
                <a href="{{ route('absensi.riwayat') }}" 
                   class="inline-flex items-center justify-center text-white bg-blue-600 hover:bg-blue-700 rounded-full w-12 h-12 shadow-md -mt-4 transition-colors duration-200">
                    <i class="fas fa-history"></i>
                </a>
            </div>
            
            <button id="mobile-export-toggle" 
                    class="inline-flex flex-col items-center justify-center px-2 hover:bg-gray-50 group transition-colors duration-200">
                <i class="fas fa-file-export text-gray-600 group-hover:text-blue-600 text-sm mb-1"></i>
                <span class="text-xs text-gray-600 group-hover:text-blue-600">Export</span>
            </button>
            
            <!-- Akun dengan dropdown yang tidak mengganggu layout -->
            <div class="relative flex items-center justify-center">
                <button id="mobile-account-toggle" 
                        class="inline-flex flex-col items-center justify-center px-2 hover:bg-gray-50 group transition-colors duration-200 w-full h-full">
                    <i class="fas fa-user-circle text-gray-600 group-hover:text-blue-600 text-sm mb-1"></i>
                    <span class="text-xs text-gray-600 group-hover:text-blue-600">Akun</span>
                </button>
                
                <!-- User Dropdown Mobile -->
                <div id="mobile-account-dropdown" 
                     class="absolute bottom-full mb-2 right-0 w-48 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible transition-all duration-200 border border-gray-200 transform scale-95">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('petugas')->user()->username }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Management Modal Mobile -->
    <div id="data-management-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end justify-center opacity-0 invisible transition-opacity duration-300 md:hidden">
        <div class="bg-white w-full max-w-md rounded-t-2xl p-4 transform translate-y-full transition-transform duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Kelola Data</h3>
                <button id="close-data-modal" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4 max-h-96 overflow-y-auto">
                <!-- Peserta Section -->
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-users mr-1"></i>Peserta
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('peserta.index') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-list mr-3 text-gray-500"></i>
                            Daftar Peserta
                        </a>
                        <a href="{{ route('peserta.create') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-user-plus mr-3 text-green-500"></i>
                            Tambah Peserta
                        </a>
                    </div>
                </div>

                <!-- Program Section -->
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-book mr-1"></i>Program
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('program.index') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-list mr-3 text-gray-500"></i>
                            Daftar Program
                        </a>
                        <a href="{{ route('program.create') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-plus-circle mr-3 text-green-500"></i>
                            Tambah Program
                        </a>
                    </div>
                </div>

                <!-- Kegiatan Section -->
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Kegiatan
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('kegiatan.index') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-list mr-3 text-gray-500"></i>
                            Daftar Kegiatan
                        </a>
                        <a href="{{ route('kegiatan.create') }}" 
                           class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-plus-circle mr-3 text-green-500"></i>
                            Tambah Kegiatan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal Mobile -->
    <div id="export-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end justify-center opacity-0 invisible transition-opacity duration-300 md:hidden">
        <div class="bg-white w-full max-w-md rounded-t-2xl p-4 transform translate-y-full transition-transform duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Data Transfer</h3>
                <button id="close-export-modal" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('export.form') }}" 
                   class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-3 rounded-md text-base bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-download mr-3 text-blue-500"></i>
                    Export/Import Data
                </a>
                <a href="{{ route('export.peserta.template') }}" 
                   class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-3 rounded-md text-base bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-file-excel mr-3 text-green-500"></i>
                    Template Import
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 pb-20 md:pb-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
            <p class="text-gray-600 mt-1">@yield('subtitle')</p>
        </div>

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                
                // Ubah icon ketika menu terbuka/tertutup
                const icon = mobileMenuButton.querySelector('i');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            });
        }

        // Data management accordion toggle
        const dataManagementToggle = document.getElementById('data-management-toggle');
        const dataManagementMenu = document.getElementById('data-management-menu');
        
        if (dataManagementToggle && dataManagementMenu) {
            dataManagementToggle.addEventListener('click', () => {
                dataManagementMenu.classList.toggle('hidden');
                const icon = dataManagementToggle.querySelector('i.fa-chevron-down');
                icon.classList.toggle('rotate-180');
            });
        }

        // Modal functions
        function openModal(modal) {
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('translate-y-full');
            }, 50);
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modal) {
            modal.querySelector('div').classList.add('translate-y-full');
            setTimeout(() => {
                modal.classList.add('invisible', 'opacity-0');
                modal.classList.remove('visible', 'opacity-100');
            }, 300);
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Data management modal
        const dataManagementModal = document.getElementById('data-management-modal');
        const mobileDataToggle = document.getElementById('mobile-data-toggle');
        const closeDataModal = document.getElementById('close-data-modal');
        
        if (mobileDataToggle && dataManagementModal) {
            mobileDataToggle.addEventListener('click', () => openModal(dataManagementModal));
            closeDataModal.addEventListener('click', () => closeModal(dataManagementModal));
            dataManagementModal.addEventListener('click', (e) => {
                if (e.target === dataManagementModal) closeModal(dataManagementModal);
            });
        }

        // Export modal
        const exportModal = document.getElementById('export-modal');
        const mobileExportToggle = document.getElementById('mobile-export-toggle');
        const closeExportModal = document.getElementById('close-export-modal');
        
        if (mobileExportToggle && exportModal) {
            mobileExportToggle.addEventListener('click', () => openModal(exportModal));
            closeExportModal.addEventListener('click', () => closeModal(exportModal));
            exportModal.addEventListener('click', (e) => {
                if (e.target === exportModal) closeModal(exportModal);
            });
        }

        // Mobile account dropdown toggle
        const mobileAccountToggle = document.getElementById('mobile-account-toggle');
        const mobileAccountDropdown = document.getElementById('mobile-account-dropdown');
        
        if (mobileAccountToggle && mobileAccountDropdown) {
            mobileAccountToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const isVisible = mobileAccountDropdown.classList.contains('show');
                
                // Toggle dropdown akun
                if (isVisible) {
                    mobileAccountDropdown.classList.remove('show');
                    mobileAccountToggle.classList.remove('active');
                } else {
                    mobileAccountDropdown.classList.add('show');
                    mobileAccountToggle.classList.add('active');
                }
            });

            // Tutup dropdown ketika klik di luar
            document.addEventListener('click', (e) => {
                if (!mobileAccountToggle.contains(e.target) && !mobileAccountDropdown.contains(e.target)) {
                    mobileAccountDropdown.classList.remove('show');
                    mobileAccountToggle.classList.remove('active');
                }
            });
        }

        // Close dropdown when clicking outside (desktop)
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.relative.group');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    const menu = dropdown.querySelector('.absolute');
                    if (menu) {
                        menu.classList.remove('opacity-100', 'visible');
                        menu.classList.add('opacity-0', 'invisible');
                    }
                }
            });
        });

        // Close dropdown after clicking a link (desktop)
        document.querySelectorAll('.relative.group .absolute a').forEach(link => {
            link.addEventListener('click', function() {
                const dropdown = this.closest('.relative.group');
                const menu = dropdown.querySelector('.absolute');
                menu.classList.remove('opacity-100', 'visible');
                menu.classList.add('opacity-0', 'invisible');
            });
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed.bg-green-500, .fixed.bg-red-500');
            messages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 5000);

        // Handle page scroll - ensure bottom nav stays visible
        let lastScrollTop = 0;
        const bottomNav = document.querySelector('.fixed.z-40');
        
        if (bottomNav) {
            window.addEventListener('scroll', function() {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down - hide bottom nav
                    bottomNav.style.transform = 'translateY(100%)';
                } else {
                    // Scrolling up - show bottom nav
                    bottomNav.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop;
            }, { passive: true });
        }

        // Logo entrance animations
        document.addEventListener('DOMContentLoaded', function() {
            const logoDesktop = document.querySelector('.logo-container-desktop');
            const logoMobile = document.querySelector('.logo-mobile-container');
            
            if (logoDesktop) {
                logoDesktop.classList.add('logo-entrance');
                setTimeout(() => logoDesktop.classList.remove('logo-entrance'), 800);
            }
            
            if (logoMobile) {
                logoMobile.classList.add('logo-entrance-mobile');
                setTimeout(() => logoMobile.classList.remove('logo-entrance-mobile'), 600);
            }
        });
    </script>
</body>

<style>
    /* ===== PAGINATION STYLES ===== */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }
    
    .pagination li {
        margin: 0 0.25rem;
    }
    
    .pagination li a,
    .pagination li span {
        display: block;
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination li a:hover {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .pagination li.active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    /* ===== ANIMATIONS ===== */
    /* Bottom Navigation */
    .fixed.z-40 {
        transition: transform 0.3s ease-in-out;
    }

    /* Flash Messages */
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    @keyframes slide-down {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-down {
        animation: slide-down 0.3s ease-out;
    }

    /* Lightning Flash Effect untuk Mobile */
    @keyframes lightning-flash {
        0% {
            transform: translateX(-100%) skewX(-15deg);
            opacity: 0;
        }
        50% {
            transform: translateX(0%) skewX(-15deg);
            opacity: 0.6;
        }
        100% {
            transform: translateX(100%) skewX(-15deg);
            opacity: 0;
        }
    }

    .animate-lightning-flash {
        animation: lightning-flash 0.8s ease-in-out;
    }

    /* ===== LAYOUT & POSITIONING ===== */
    /* Modal Z-index */
    #data-management-modal,
    #export-modal {
        z-index: 60;
    }

    #data-management-modal > div,
    #export-modal > div {
        z-index: 70;
    }

    /* Mobile Account Dropdown */
    #mobile-account-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    /* Bottom Navigation Items */
    .fixed.z-40 .grid > * {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
    }

    /* Active State untuk Mobile Account */
    #mobile-account-toggle.active {
        background-color: #f3f4f6;
    }

    #mobile-account-toggle.active i,
    #mobile-account-toggle.active span {
        color: #3b82f6;
    }

    /* ===== LOGO ANIMATIONS ===== */
    /* Desktop Logo Entrance */
    @keyframes logo-entrance {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .logo-entrance {
        animation: logo-entrance 0.8s ease-out;
    }

    /* Mobile Logo Entrance */
    @keyframes logo-entrance-mobile {
        from {
            opacity: 0;
            transform: scale(0.9) translateX(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateX(0);
        }
    }

    .logo-entrance-mobile {
        animation: logo-entrance-mobile 0.6s ease-out;
    }

    /* ===== LOGO EFFECTS ===== */
    /* Desktop Logo */
    .logo-container-desktop {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .logo-image {
        border-radius: 8px;
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .logo-container-desktop:hover .logo-image {
        transform: scale(1.1) rotate(3deg);
        filter: brightness(1.1) drop-shadow(0 10px 20px rgba(0,0,0,0.1));
    }

    /* Mobile Logo */
    .logo-mobile-container {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .logo-mobile-image {
        border-radius: 6px;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .logo-mobile-container:hover .logo-mobile-image {
        transform: scale(1.08) rotate(2deg);
        filter: brightness(1.05) drop-shadow(0 5px 10px rgba(0,0,0,0.1));
    }

    /* ===== RESPONSIVE ADJUSTMENTS ===== */
    @media (max-width: 480px) {
        .logo-mobile-container .logo-mobile-image {
            height: 40px;
            width: 40px;
        }
        
        .logo-mobile-container:hover .logo-mobile-image {
            transform: scale(1.05) rotate(1deg);
        }
    }

    /* ===== CLEANUP - Hapus animasi yang tidak digunakan ===== */
    .animate-lightning,
    .animate-sparkle {
        display: none;
    }
</style>
</html>