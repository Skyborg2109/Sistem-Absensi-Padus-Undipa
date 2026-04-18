@extends('layouts.app')

@section('title', 'Admin Dashboard - Universitas Dipa Makassar Choir')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <h2 class="font-headline text-4xl lg:text-5xl font-extrabold text-on-surface tracking-tight mb-2">Selamat datang, {{ auth()->user()->name }} 👋</h2>
        <p class="font-body text-on-surface-variant text-lg">Berikut adalah ritme paduan suara Anda hari ini.</p>
    </div>
    <div class="flex flex-col md:flex-row items-end md:items-center gap-4">
        <a href="{{ route('admin.attendance.export') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white border border-slate-200 shadow-sm hover:bg-slate-50 text-slate-700 font-headline font-semibold text-sm tracking-wide transition-all duration-300">
            <span class="material-symbols-outlined text-base">download</span>
            Download Laporan (.csv)
        </a>
        <div class="flex items-center gap-4 bg-surface-container-lowest p-2 rounded-full shadow-[0_8px_32px_rgba(0,10,30,0.06)] hidden md:flex">
            <img class="w-10 h-10 rounded-full object-cover border-2 border-surface" data-alt="Professional portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ1Rz_lY4vySgEWOW4pBrXKcw6rWgVBl9y2Ix8TY0pZL-Ri3Et_vBFAZN9DqnDr8Vy4ZXcTLutU_enqNPF8QIn3Cy4Zleh0MoD67MQ6PfH3Wkxkxc1jTtPuQoL14sqT3MJLDkoy-0pFv1w4p-0sUBWEJNmpFb_c5rGAUso_WZbH_Ho_gysVichTrABvGACwmfdWNAghLdLKe6GpKNK_BH1FHqdhAvEWwT2qWTysWDQzyybaa3nIkoi2l3gp0uB20UU1ZN6BphNjLA"/>
            <div class="pr-4">
                <p class="font-headline font-bold text-sm text-on-surface leading-tight">{{ auth()->user()->name }}</p>
                <p class="font-body text-xs text-on-surface-variant">Administrator</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <!-- Stat Card 1 -->
    <div class="bg-surface-container-lowest rounded-xl p-6 relative overflow-hidden group shadow-[0_4px_24px_rgba(0,10,30,0.04)]">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-tertiary-container"></div>
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-secondary-container rounded-lg text-on-secondary-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
        <div>
            <p class="font-body text-on-surface-variant text-sm font-medium mb-1">Total Anggota</p>
            <h3 class="font-headline text-4xl font-extrabold text-primary tracking-tight">{{ $total_members ?? 0 }}</h3>
        </div>
        <!-- Decorative background element -->
        <div class="absolute -right-4 -bottom-4 text-secondary-container opacity-20 pointer-events-none group-hover:scale-110 transition-transform duration-500">
            <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">group</span>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-surface-container-lowest rounded-xl p-6 relative overflow-hidden group shadow-[0_4px_24px_rgba(0,10,30,0.04)]">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">event</span>
            </div>
        </div>
        <div>
            <p class="font-body text-on-surface-variant text-sm font-medium mb-1">Sesi Aktif</p>
            <h3 class="font-headline text-4xl font-extrabold text-primary tracking-tight">{{ $active_schedules ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 text-tertiary-fixed opacity-20 pointer-events-none group-hover:scale-110 transition-transform duration-500">
            <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">event</span>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-surface-container-lowest rounded-xl p-6 relative overflow-hidden group shadow-[0_4px_24px_rgba(0,10,30,0.04)]">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-primary-fixed rounded-lg text-on-primary-fixed">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <span class="inline-flex items-center gap-1 bg-surface-container px-2 py-1 rounded text-xs font-medium text-success">
                <span class="material-symbols-outlined text-[14px]">arrow_upward</span> 12%
            </span>
        </div>
        <div>
            <p class="font-body text-on-surface-variant text-sm font-medium mb-1">Kehadiran Hari Ini</p>
            <div class="flex items-baseline gap-2">
                <h3 class="font-headline text-4xl font-extrabold text-primary tracking-tight">42</h3>
                <span class="font-body text-on-surface-variant text-sm">/ 50</span>
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 text-primary-fixed opacity-20 pointer-events-none group-hover:scale-110 transition-transform duration-500">
            <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_4px_24px_rgba(0,10,30,0.04)]">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h3 class="font-headline text-xl font-bold text-on-surface">Kehadiran Mingguan</h3>
            <p class="font-body text-sm text-on-surface-variant mt-1">Gambaran partisipasi latihan minggu ini.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-lg bg-surface text-on-surface font-body text-sm font-medium hover:bg-surface-container transition-colors">Minggu</button>
            <button class="px-4 py-2 rounded-lg bg-transparent text-on-surface-variant font-body text-sm font-medium hover:bg-surface-container transition-colors">Bulan</button>
        </div>
    </div>

    <!-- Placeholder Visualization -->
    <div class="h-64 flex items-end justify-between gap-2 sm:gap-6 pt-4 relative border-b-2 border-surface-variant">
        <!-- Grid Lines Background -->
        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-0">
            <div class="w-full border-t border-surface-variant/50"></div>
            <div class="w-full border-t border-surface-variant/50"></div>
            <div class="w-full border-t border-surface-variant/50"></div>
            <div class="w-full border-t border-surface-variant/50"></div>
        </div>

        <!-- Bars -->
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] bg-secondary-fixed rounded-t-md h-[60%] group-hover:bg-primary transition-colors relative">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded">30</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-medium">Sen</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] bg-secondary-fixed rounded-t-md h-[85%] group-hover:bg-primary transition-colors relative">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded">42</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-medium">Sel</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] bg-secondary-fixed rounded-t-md h-[40%] group-hover:bg-primary transition-colors relative">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded">20</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-medium">Rab</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] bg-gradient-to-t from-primary-container to-primary rounded-t-md h-[90%] relative shadow-[0_0_16px_rgba(0,10,30,0.2)]">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded">45</div>
            </div>
            <span class="font-body text-xs text-primary font-bold">Kam</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] bg-secondary-fixed rounded-t-md h-[70%] group-hover:bg-primary transition-colors relative">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-inverse-surface text-inverse-on-surface text-xs py-1 px-2 rounded">35</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-medium">Jum</span>
        </div>
    </div>
</div>
@endsection
