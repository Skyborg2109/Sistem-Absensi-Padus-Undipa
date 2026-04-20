@extends('layouts.app')

@section('title', 'Admin Dashboard - Universitas Dipa Makassar Choir')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
    <div>
        <p class="text-xs font-bold text-undipa-gold uppercase tracking-[0.2em] mb-1">Panel Administrator</p>
        <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-undipa-navy tracking-tight mb-2">Selamat datang, {{ auth()->user()->name }} 👋</h2>
        <p class="font-body text-on-surface-variant">Berikut adalah ritme paduan suara Anda hari ini.</p>
    </div>
    <div class="flex flex-col md:flex-row items-end md:items-center gap-4">
        <div class="flex items-center gap-3 bg-undipa-navy/5 border border-undipa-navy/10 p-2 pl-3 rounded-full hidden md:flex">
            <div>
                <p class="font-headline font-bold text-sm text-undipa-navy leading-tight">{{ auth()->user()->name }}</p>
                <p class="font-body text-xs text-undipa-gold font-semibold">Administrator</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-undipa-navy flex items-center justify-center mr-1">
                <span class="material-symbols-outlined text-undipa-gold text-[18px]" style="font-variation-settings: 'FILL' 1;">shield</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
    <!-- Stat Card 1: Total Anggota -->
    <div class="bg-white rounded-2xl p-6 relative overflow-hidden group stat-card-glow border border-blue-50">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 gold-accent-bar rounded-l-2xl"></div>
        <div class="flex justify-between items-start mb-5">
            <div class="p-3 rounded-xl" style="background: linear-gradient(135deg, #003087 0%, #0040a8 100%);">
                <span class="material-symbols-outlined text-undipa-gold text-[22px]" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-blue-50 text-undipa-navy uppercase tracking-wider">Anggota</span>
        </div>
        <div>
            <p class="font-body text-on-surface-variant text-xs font-medium mb-1 uppercase tracking-wider">Total Anggota</p>
            <h3 class="font-headline text-5xl font-extrabold text-undipa-navy tracking-tight">{{ $total_members ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none group-hover:scale-110 group-hover:opacity-10 transition-all duration-500">
            <span class="material-symbols-outlined text-[100px] text-undipa-navy" style="font-variation-settings: 'FILL' 1;">group</span>
        </div>
    </div>

    <!-- Stat Card 2: Sesi Aktif -->
    <div class="bg-undipa-navy rounded-2xl p-6 relative overflow-hidden group shadow-xl shadow-undipa-navy/30">
        <div class="flex justify-between items-start mb-5">
            <div class="p-3 rounded-xl bg-white/15">
                <span class="material-symbols-outlined text-undipa-gold text-[22px]" style="font-variation-settings: 'FILL' 1;">event</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-undipa-gold/20 text-undipa-gold uppercase tracking-wider">Aktif</span>
        </div>
        <div>
            <p class="font-body text-blue-200 text-xs font-medium mb-1 uppercase tracking-wider">Sesi Aktif</p>
            <h3 class="font-headline text-5xl font-extrabold text-white tracking-tight">{{ $active_schedules ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10 pointer-events-none group-hover:scale-110 group-hover:opacity-20 transition-all duration-500">
            <span class="material-symbols-outlined text-[100px] text-undipa-gold" style="font-variation-settings: 'FILL' 1;">event</span>
        </div>
    </div>

    <!-- Stat Card 3: Kehadiran -->
    <div class="bg-white rounded-2xl p-6 relative overflow-hidden group stat-card-glow border border-blue-50">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 navy-accent-bar rounded-l-2xl"></div>
        <div class="flex justify-between items-start mb-5">
            <div class="p-3 rounded-xl" style="background: linear-gradient(135deg, #C8A84B 0%, #E8C96A 100%);">
                <span class="material-symbols-outlined text-undipa-navy text-[22px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <span class="inline-flex items-center gap-1 bg-green-50 border border-green-200 px-2 py-1 rounded-full text-[10px] font-bold text-green-700">
                <span class="material-symbols-outlined text-[12px]">arrow_upward</span> 12%
            </span>
        </div>
        <div>
            <p class="font-body text-on-surface-variant text-xs font-medium mb-1 uppercase tracking-wider">Kehadiran Hari Ini</p>
            <div class="flex items-baseline gap-2">
                <h3 class="font-headline text-5xl font-extrabold text-undipa-navy tracking-tight">42</h3>
                <span class="font-body text-on-surface-variant text-sm font-semibold">/ 50</span>
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none group-hover:scale-110 group-hover:opacity-10 transition-all duration-500">
            <span class="material-symbols-outlined text-[100px] text-undipa-gold" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-white rounded-2xl p-8 stat-card-glow border border-blue-50">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <p class="text-[10px] font-bold text-undipa-gold uppercase tracking-[0.2em] mb-1">Statistik</p>
            <h3 class="font-headline text-xl font-bold text-undipa-navy">Kehadiran Mingguan</h3>
            <p class="font-body text-sm text-on-surface-variant mt-1">Gambaran partisipasi latihan minggu ini.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-lg bg-undipa-navy text-white font-body text-sm font-semibold transition-colors">Minggu</button>
            <button class="px-4 py-2 rounded-lg bg-transparent text-on-surface-variant border border-slate-200 font-body text-sm font-medium hover:bg-surface-container transition-colors">Bulan</button>
        </div>
    </div>
    <!-- Legend -->
    <div class="flex gap-4 mb-6">
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-sm" style="background: #dbeafe;"></div>
            <span class="text-xs text-on-surface-variant font-medium">Hadir</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-sm" style="background: linear-gradient(to top, #C8A84B, #E8C96A);"></div>
            <span class="text-xs text-undipa-gold font-bold">Tertinggi</span>
        </div>
    </div>

    <!-- Chart Bars -->
    <div class="h-56 flex items-end justify-between gap-2 sm:gap-6 pt-4 relative border-b-2 border-blue-100">
        <!-- Grid Lines -->
        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-0">
            <div class="w-full border-t border-blue-50"></div>
            <div class="w-full border-t border-blue-50"></div>
            <div class="w-full border-t border-blue-50"></div>
            <div class="w-full border-t border-blue-50"></div>
        </div>

        <!-- Bars -->
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] rounded-t-lg h-[60%] group-hover:opacity-80 transition-all relative" style="background: #dbeafe; border: 1px solid #bfdbfe;">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-undipa-navy text-white text-xs py-1 px-2 rounded-lg">30</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-semibold">Sen</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] rounded-t-lg h-[85%] group-hover:opacity-80 transition-all relative" style="background: #dbeafe; border: 1px solid #bfdbfe;">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-undipa-navy text-white text-xs py-1 px-2 rounded-lg">42</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-semibold">Sel</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] rounded-t-lg h-[40%] group-hover:opacity-80 transition-all relative" style="background: #dbeafe; border: 1px solid #bfdbfe;">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-undipa-navy text-white text-xs py-1 px-2 rounded-lg">20</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-semibold">Rab</span>
        </div>
        <!-- Highest bar (gold) -->
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] chart-bar-gold rounded-t-lg h-[90%] relative shadow-lg shadow-undipa-gold/30">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-undipa-gold text-undipa-navy-dark font-bold text-xs py-1 px-2 rounded-lg">45</div>
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2">
                    <span class="material-symbols-outlined text-undipa-navy text-[12px]" style="font-variation-settings: 'FILL' 1;">star</span>
                </div>
            </div>
            <span class="font-body text-xs text-undipa-gold font-extrabold">Kam</span>
        </div>
        <div class="w-full flex flex-col items-center gap-2 group z-10">
            <div class="w-full max-w-[48px] rounded-t-lg h-[70%] group-hover:opacity-80 transition-all relative" style="background: #dbeafe; border: 1px solid #bfdbfe;">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-undipa-navy text-white text-xs py-1 px-2 rounded-lg">35</div>
            </div>
            <span class="font-body text-xs text-on-surface-variant font-semibold">Jum</span>
        </div>
    </div>
</div>
@endsection
