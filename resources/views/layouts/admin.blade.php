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
    <!-- Header -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <i class="fas fa-qrcode text-blue-600 text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-gray-800">ABSENSI BARCODE</span>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    
                    <a href="{{ route('absensi.riwayat') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-history mr-1"></i>Riwayat
                    </a>

                    <!-- Data Management Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-database mr-1"></i>Kelola Data
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-200">
                            <!-- Peserta Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                <i class="fas fa-users mr-1"></i>Peserta
                            </div>
                            <a href="{{ route('peserta.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Peserta
                            </a>
                            <a href="{{ route('peserta.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-user-plus mr-2 text-green-500"></i>
                                Tambah Peserta
                            </a>

                            <!-- Jadwal Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i>Jadwal
                            </div>
                            <a href="{{ route('jadwal.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Jadwal
                            </a>
                            <a href="{{ route('jadwal.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                                Buat Jadwal
                            </a>

                            <!-- Mata Kuliah Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-book mr-1"></i>Mata Kuliah
                            </div>
                            <a href="{{ route('mata-kuliah.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-list mr-2 text-gray-500"></i>
                                Daftar Mata Kuliah
                            </a>
                            <a href="{{ route('mata-kuliah.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                                Tambah Mata Kuliah
                            </a>

                            <!-- Export/Import Section -->
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                <i class="fas fa-file-export mr-1"></i>Data Transfer
                            </div>
                            <a href="{{ route('export.form') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-download mr-2 text-blue-500"></i>
                                Export/Import Data
                            </a>
                            <a href="{{ route('export.peserta.template') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-file-excel mr-2 text-green-500"></i>
                                Template Import
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center text-sm text-gray-700 hover:text-blue-600">
                            <i class="fas fa-user-circle mr-1"></i>
                            {{ Auth::guard('petugas')->user()->username }}
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('petugas')->user()->username }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
            <p class="text-gray-600">@yield('subtitle')</p>
        </div>

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-transform duration-300 ease-in-out z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-transform duration-300 ease-in-out z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed');
            messages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 5000);

        // Close dropdown when clicking outside
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

        // Optional: Close dropdown after clicking a link
        document.querySelectorAll('.relative.group .absolute a').forEach(link => {
            link.addEventListener('click', function() {
                const dropdown = this.closest('.relative.group');
                const menu = dropdown.querySelector('.absolute');
                menu.classList.remove('opacity-100', 'visible');
                menu.classList.add('opacity-0', 'invisible');
            });
        });
    </script>
</body>

<style>
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
</style>
</html>