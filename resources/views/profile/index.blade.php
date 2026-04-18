@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 mt-8 mb-24">
    <!-- Header Page -->
    <div class="flex flex-col gap-1 mb-8">
        <h1 class="font-headline text-4xl font-extrabold text-primary tracking-tight">Profil Pengguna</h1>
        <p class="text-on-surface-variant font-body text-base mt-1">Kelola data pribadi dan preferensi akun Anda.</p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-100" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Card Layout -->
    <div class="bg-surface-container-lowest rounded-3xl p-8 lg:p-12 shadow-sm border border-slate-100 flex flex-col md:flex-row gap-12 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 blur-3xl rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

        <!-- Left: Avatar & Quick Info -->
        <div class="flex flex-col items-center text-center space-y-4 md:w-1/3">
            <div class="relative w-32 h-32 rounded-full p-1 border-4 border-surface-container-high bg-white shadow-md">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMS-Jw1uWJ4xxtA-LPZI9F2ZiRIt6PxceB1tsOBUzOs6V_ul5XGvq-cdLKPfuIWOT6uDGqYqOl6rp0YIof7WRWFx6HYgjtNAvFkj_AYU7LrYXCkGzx13NMi7ufRlWGXsvmCDzo8IXY6P7kN8AwNCZDHi3GSlSqvy20kLZekGjxbj2LiZoG7h10gJ1nV75gUVvGaVMfaLhL6nN0bqxx8XNRDPuviP-xffFsZS3r2TqTEm12O3XofACQ2JiHLttP0QJrvCRpS9SPupE" alt="Profile avatar" class="w-full h-full object-cover rounded-full" />
                <button class="absolute bottom-0 right-0 p-2 bg-primary text-on-primary rounded-full shadow-lg hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
            </div>
            <div>
                <h2 class="font-headline text-xl font-bold text-on-surface">{{ auth()->user()->name }}</h2>
                <p class="text-sm font-medium text-primary mt-1">{{ auth()->user()->voice_part ?? 'Belum ditentukan' }}</p>
                <div class="inline-flex mt-3 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                    {{ auth()->user()->status_anggota ?? 'Aktif' }}
                </div>
            </div>
            
            <hr class="w-full border-surface-container-high my-4">
            
            <div class="w-full flex justify-between px-4 text-sm">
                <span class="text-on-surface-variant font-medium">Bergabung</span>
                <span class="font-bold text-on-surface">{{ auth()->user()->created_at->format('M Y') }}</span>
            </div>
        </div>

        <!-- Right: Form Details -->
        <form action="{{ route('settings.update') }}" method="POST" class="flex-1 flex flex-col gap-6">
            @csrf
            @method('PUT')
            <h3 class="font-headline text-lg font-bold text-on-surface border-b border-surface-container-high pb-2">Detail Informasi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Field -->
                <div class="flex flex-col gap-1.5 focus-within:text-primary">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary text-sm font-medium text-on-surface transition-all" />
                </div>
                <!-- Field -->
                <div class="flex flex-col gap-1.5 focus-within:text-primary">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', auth()->user()->nim) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary text-sm font-medium text-on-surface transition-all" />
                </div>
                <!-- Field -->
                <div class="flex flex-col gap-1.5 focus-within:text-primary md:col-span-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary text-sm font-medium text-on-surface transition-all" />
                </div>
                <!-- Field -->
                <div class="flex flex-col gap-1.5 focus-within:text-primary">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">No. Telepon / WA</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary text-sm font-medium text-on-surface transition-all" />
                </div>
                <!-- Field -->
                <div class="flex flex-col gap-1.5 focus-within:text-primary">
                    <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Fakultas / Jurusan</label>
                    <input type="text" name="faculty" value="{{ old('faculty', auth()->user()->faculty) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary text-sm font-medium text-on-surface transition-all" />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-4 pt-6 border-t border-surface-container-high">
                <a href="{{ url('/') }}" class="px-6 py-2.5 rounded-full font-bold text-sm bg-surface-container-high text-on-surface-variant hover:bg-surface-variant transition-colors flex items-center justify-center">
                    Kembali
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full font-bold text-sm bg-gradient-to-r from-primary to-primary-container text-on-primary shadow-md hover:opacity-90 transition-opacity">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
