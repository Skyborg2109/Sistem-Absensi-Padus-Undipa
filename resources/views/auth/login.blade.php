@extends('layouts.guest')

@section('title', 'Sistem Absensi Paduan Suara - Login')

@section('content')
<!-- Elevated Card Container -->
<div class="bg-surface-container-lowest/80 backdrop-blur-xl rounded-xl shadow-[0_32px_64px_rgba(0,10,30,0.06)] overflow-hidden border border-surface-container-high/50 p-8 sm:p-10">
    <!-- Logo & Brand Header -->
    <div class="flex flex-col items-center mb-10">
        <div class="w-20 h-20 bg-surface-container-low rounded-full flex items-center justify-center mb-6 shadow-inner relative overflow-hidden">
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhd7QGx1YJVOs_x9O456oduan3IQcnpeaOTHrtG-FQNVBgIyVh37lDazvNdYISs8m4C9WfjwV5KXFtsrECLIzRYFVN_D2T_TmROQGIH5P5Sct-7CvvGrS1lXkiqXCeLYVElodfk04hxXc2X6IBBiWUdiU7fwnL2K-Bxkn3GMBENWXyORX5mT2JkR3Ne/s955/LogoUndipa.png" alt="Logo Undipa" class="w-full h-full object-cover">
        </div>
        <h1 class="font-headline text-3xl font-bold tracking-tight text-on-surface text-center mb-2">Sistem Absensi Paduan Suara</h1>
        <p class="font-body text-sm text-on-surface-variant text-center">Universitas Dipa Makassar</p>
    </div>
    
    <!-- Login Form -->
    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf
        <!-- Email Field -->
        <div class="space-y-2">
            <label class="block font-label text-sm font-medium text-on-surface" for="email">Email / Nama Pengguna</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl">person</span>
                </div>
                <input class="block w-full pl-12 pr-4 py-3 bg-surface-container-low border-none rounded-lg text-on-surface font-body text-base focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-colors placeholder:text-on-surface-variant/50" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" type="email" />
            </div>
            @error('email')
                <p class="text-error text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Password Field -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label class="block font-label text-sm font-medium text-on-surface" for="password">Kata Sandi</label>
                <a class="font-label text-sm font-medium text-tertiary hover:text-tertiary-container transition-colors" href="#">Lupa Kata Sandi?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl">lock</span>
                </div>
                <input class="block w-full pl-12 pr-12 py-3 bg-surface-container-low border-none rounded-lg text-on-surface font-body text-base focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-colors placeholder:text-on-surface-variant/50" id="password" name="password" placeholder="••••••••" type="password" />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                    <button id="togglePassword" class="text-on-surface-variant hover:text-on-surface transition-colors focus:outline-none" type="button">
                        <span id="toggleIcon" class="material-symbols-outlined text-xl">visibility_off</span>
                    </button>
                </div>
            </div>
            @error('password')
                <p class="text-error text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="pt-4">
            <button class="w-full flex items-center justify-center py-3.5 px-6 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-semibold text-base shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-fixed" type="submit">
                Masuk
                <span class="material-symbols-outlined ml-2 text-xl">arrow_forward</span>
            </button>
        </div>
    </form>
    
    <!-- Contextual Information -->
    <div class="mt-8 pt-6 border-t border-surface-container-high/50 text-center">
        <p class="font-body text-xs text-on-surface-variant flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-sm">verified</span>
            Akses aman hanya untuk anggota resmi
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (togglePassword && passwordInput && toggleIcon) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });
        }
    });
</script>
@endsection
