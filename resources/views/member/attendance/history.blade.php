@extends('layouts.app')

@section('title', 'Attendance Ledger - Member Portal')

@section('content')
<div class="max-w-[1000px] mx-auto space-y-10 mt-8 mb-24">
    <!-- Page Title -->
    <div class="flex flex-col gap-1">
        <h1 class="font-headline text-4xl md:text-[2.5rem] font-extrabold text-primary tracking-[-0.02em] leading-tight">Riwayat Absensi</h1>
        <p class="text-on-surface-variant font-body text-base mt-1 max-w-2xl">Tinjau komitmen latihan historis Anda dan status keterlibatan keseluruhan untuk musim saat ini.</p>
    </div>
    
    <!-- Asymmetric Bento Summary -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Primary Metric Card -->
        <div class="md:col-span-8 bg-surface-container-lowest rounded-[1.5rem] p-8 relative overflow-hidden flex flex-col justify-between min-h-[220px] shadow-sm border border-slate-100">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-tertiary-container"></div>
            <div class="flex justify-between items-start relative z-10 w-full">
                <p class="text-on-surface-variant font-headline text-sm uppercase tracking-wider font-bold">Status Keseluruhan</p>
                <span class="inline-flex items-center gap-1.5 bg-surface-container px-3 py-1.5 rounded-full text-xs font-semibold text-primary">
                    <span class="material-symbols-outlined text-[16px]">monitoring</span>
                    Sangat Baik
                </span>
            </div>
            <div class="relative z-10 mt-6">
                <div class="flex items-baseline gap-1">
                    <span class="font-headline text-7xl md:text-[5.5rem] font-black text-primary tracking-tighter leading-none">{{ count($attendances) > 0 ? round(($attendances->where('status', 'Hadir')->count() / count($attendances)) * 100) : 0 }}</span>
                    <span class="font-headline text-3xl text-on-surface-variant font-bold">%</span>
                </div>
                <p class="text-sm font-body text-on-surface-variant mt-2 font-medium">Tingkat Kehadiran • {{ $attendances->where('status', 'Hadir')->count() }} dari {{ count($attendances) }} sesi</p>
            </div>
            <!-- Ambient Texture -->
            <div class="absolute right-[-10%] bottom-[-20%] w-[60%] h-[120%] bg-gradient-to-br from-primary-fixed/30 to-transparent rounded-full blur-[40px] pointer-events-none"></div>
        </div>
        
        <!-- Secondary Metric Cards -->
        <div class="md:col-span-4 flex flex-col gap-6">
            <!-- Present Card -->
            <div class="bg-surface-container-low rounded-[1.5rem] p-6 flex-1 flex flex-col justify-center relative overflow-hidden shadow-sm border border-slate-100">
                <p class="text-on-surface-variant font-headline text-xs font-bold uppercase tracking-wider mb-2">Total Hadir</p>
                <p class="font-headline text-4xl font-extrabold text-primary">{{ $attendances->where('status', 'Hadir')->count() }}</p>
                <span class="material-symbols-outlined absolute right-4 bottom-4 text-[48px] text-surface-container-highest/50">how_to_reg</span>
            </div>
            <!-- Split Card for Exceptions -->
            <div class="bg-surface-container-lowest rounded-[1.5rem] p-6 flex-1 flex items-center justify-between shadow-[0_8px_32px_rgba(0,10,30,0.02)] border border-slate-100">
                <div class="flex flex-col">
                    <p class="text-on-surface-variant font-headline text-xs font-bold uppercase tracking-wider mb-1">Izin / Sakit</p>
                    <p class="font-headline text-2xl font-bold text-on-surface">{{ $attendances->whereIn('status', ['Izin', 'Sakit'])->count() }}</p>
                </div>
                <div class="h-10 w-[1px] bg-surface-container-high"></div>
                <div class="flex flex-col text-right">
                    <p class="text-on-surface-variant font-headline text-xs font-bold uppercase tracking-wider mb-1">Alpha</p>
                    <p class="font-headline text-2xl font-bold text-error">{{ $attendances->where('status', 'Alpha')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ledger List Section -->
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-end px-1">
            <h2 class="font-headline text-2xl font-bold text-primary tracking-tight">Latihan Terkini</h2>
            <button class="text-sm font-bold text-tertiary hover:text-tertiary-container transition-colors flex items-center gap-1.5 pb-1">
                Filter
                <span class="material-symbols-outlined text-[18px]">tune</span>
            </button>
        </div>
        
        <!-- List Container -->
        <div class="bg-surface-container-lowest rounded-[1.5rem] p-3 md:p-6 shadow-[0_8px_32px_rgba(0,10,30,0.03)] border border-slate-100 overflow-x-auto">
            <div class="min-w-[700px] flex flex-col gap-2">
                <!-- Header Row -->
                <div class="grid grid-cols-12 gap-4 px-6 py-4 rounded-xl bg-surface-container-lowest sticky top-0 z-10 border-b border-surface-container-high">
                    <div class="col-span-3 text-xs font-headline font-bold text-on-surface-variant uppercase tracking-widest">Tanggal &amp; Waktu</div>
                    <div class="col-span-6 text-xs font-headline font-bold text-on-surface-variant uppercase tracking-widest">Detail Sesi</div>
                    <div class="col-span-3 text-xs font-headline font-bold text-on-surface-variant uppercase tracking-widest text-right">Status</div>
                </div>
                
                <!-- Dynamic Row Items -->
                @forelse($attendances as $attendance)
                <div class="grid grid-cols-12 gap-4 px-6 py-5 items-center bg-surface hover:bg-surface-container-low transition-colors rounded-xl group cursor-default relative overflow-hidden">
                    @if($attendance->status === 'Alpha')
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-error-container"></div>
                    @elseif(in_array($attendance->status, ['Izin', 'Sakit']))
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-tertiary-fixed opacity-50"></div>
                    @else
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 opacity-50"></div>
                    @endif
                    <div class="col-span-3 flex flex-col gap-1 pl-2">
                        <span class="font-headline font-bold text-primary text-sm">{{ \Carbon\Carbon::parse($attendance->schedule->date)->format('d M Y') }}</span>
                        <span class="font-body text-xs text-on-surface-variant font-medium">{{ \Carbon\Carbon::parse($attendance->schedule->date)->isoFormat('dddd') }} • {{ \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') }}</span>
                    </div>
                    <div class="col-span-6 flex flex-col gap-1 pr-4">
                        <span class="font-headline font-bold text-on-surface text-sm">{{ $attendance->schedule->title }}</span>
                        <span class="font-body text-xs text-on-surface-variant line-clamp-1">{{ $attendance->schedule->description ?? 'Tidak ada detail.' }}</span>
                    </div>
                    <div class="col-span-3 flex justify-end">
                        @if($attendance->status === 'Hadir')
                        <span class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-surface-container text-primary font-headline text-xs font-bold tracking-wide uppercase bg-green-50 text-green-700">
                            Hadir
                        </span>
                        @elseif(in_array($attendance->status, ['Izin', 'Sakit']))
                        <span class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-tertiary-fixed text-on-tertiary-fixed-variant font-headline text-xs font-bold tracking-wide uppercase bg-orange-100 text-orange-700">
                            {{ $attendance->status }}
                        </span>
                        @else
                        <span class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-error-container text-on-error-container font-headline text-xs font-bold tracking-wide uppercase bg-red-100 text-red-700">
                            {{ $attendance->status }}
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-12 text-center text-on-surface-variant">
                    Belum ada data presensi.
                </div>
                @endforelse
                
            </div>
        </div>
    </div>
</div>
@endsection
