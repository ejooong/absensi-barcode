<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login Petugas')

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
    <div class="max-w-md w-full mx-4">
        <!-- Login Section -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-22 h-22 bg-white-100 rounded-full flex items-center justify-center mb-4">
                   <img src="{{ asset('images/nasdem.png') }}" 
                     alt="ABSENSI BARCODE" 
                     style="height: 75px; width: 100px;"
                     class="mr-1 object-contain">

                   <img src="{{ asset('images/abn.png') }}" 
                     alt="ABSENSI BARCODE" 
                     style="height: 100px; width: 100px;"
                     class="mr-1 object-contain">
                                     </div>
                <h1 class="text-2xl font-bold text-gray-800"></h1>
                
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
                        placeholder="Username"
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
                        placeholder="Password"
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
                    LOGIN
                </button>
            </form>

            <!-- Link ke Share Page -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Ingin berbagi scanner? 
                    <a href="{{ route('share.scanner') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Klik di sini
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
@endsection