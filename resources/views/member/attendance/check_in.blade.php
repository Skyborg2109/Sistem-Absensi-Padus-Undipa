@extends('layouts.app')

@section('title', 'Dipa Choir - Check-in')

@section('content')
<!-- Header -->
<header class="mb-12 max-w-7xl mx-auto mt-4">
    <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-surface tracking-tight mb-2">Check-in</h1>
    <p class="text-on-surface-variant text-body-md">Verifikasi kehadiran Anda untuk latihan hari ini.</p>
</header>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto mb-20">
    <!-- Primary Action Column (QR & GPS) -->
    <div class="lg:col-span-7 flex flex-col gap-8">
        <!-- Direct Presence Actions (No QR required) -->
        <section class="bg-surface-container-low rounded-[1.5rem] p-6 lg:p-8 relative overflow-hidden shadow-sm border border-slate-100 h-full flex flex-col justify-center">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-center font-medium">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-center font-medium">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if($schedule)
                @if($alreadyCheckedIn)
                <div class="text-center">
                    @if(isset($attendanceRecord) && $attendanceRecord->status === 'Terlambat')
                        <span class="material-symbols-outlined text-[64px] text-orange-500 mb-4">gavel</span>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">Terlambat</h2>
                        <p class="text-on-surface-variant text-sm mt-2">Anda telah melakukan presensi, namun tercatat terlambat melampaui batas waktu awal sesi.</p>
                    @else
                        <span class="material-symbols-outlined text-[64px] text-green-500 mb-4">check_circle</span>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">Presensi Selesai</h2>
                        <p class="text-on-surface-variant text-sm mt-2">Anda sudah melakukan presensi untuk sesi ini ({{ isset($attendanceRecord) ? $attendanceRecord->status : 'Terekam' }}). Selamat berlatih!</p>
                    @endif
                </div>
                @elseif(!$isCheckinAllowed)
                <div class="text-center">
                    @if(isset($sessionExpired) && $sessionExpired)
                        {{-- Sesi Sudah Selesai / Expired --}}
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-5 mx-auto">
                            <span class="material-symbols-outlined text-[48px] text-red-400" style="font-variation-settings: 'FILL' 1;">event_busy</span>
                        </div>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">Sesi Telah Selesai</h2>
                        <p class="text-on-surface-variant text-sm mt-2 max-w-xs mx-auto">{{ $checkinStatusMsg }}</p>
                        <p class="text-xs text-slate-400 mt-4">Jika Anda hadir namun belum sempat presensi, silakan hubungi admin untuk pencatatan manual.</p>
                    @else
                        {{-- Belum waktunya / jadwal di masa depan --}}
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-orange-50 mb-5 mx-auto">
                            <span class="material-symbols-outlined text-[48px] text-orange-400" style="font-variation-settings: 'FILL' 1;">schedule</span>
                        </div>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">Presensi Belum Dibuka</h2>
                        <p class="text-on-surface-variant text-sm mt-2 max-w-xs mx-auto">{{ $checkinStatusMsg }}</p>
                    @endif
                </div>
                @else
                <div class="text-center mb-8">
                    <h2 class="font-headline text-2xl font-bold text-on-surface">Pilih Sesuai Kehadiran Anda</h2>
                    <p class="text-on-surface-variant text-sm mt-2">Mohon lakukan check-in dengan jujur jika Anda sudah berada di lokasi latihan.</p>
                </div>
                
                <div class="flex flex-col gap-4 max-w-sm mx-auto w-full">
                    <form action="{{ route('member.attendance.store') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="status" value="Hadir">
                        <!-- Default empty notes for 'Hadir' -->
                        <input type="hidden" name="notes" value="">
                        <button type="submit" class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl py-4 flex flex-col items-center justify-center gap-1 hover:opacity-90 transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/20 group border border-transparent">
                            <span class="material-symbols-outlined text-[32px] group-hover:-translate-y-1 transition-transform">how_to_reg</span>
                            <span class="font-headline font-bold text-lg">Saya Hadir</span>
                        </button>
                    </form>

                    <!-- Make sure to implement separate modals or fields for Sakit/Izin notes if needed in future -->
                    <form action="{{ route('member.attendance.store') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="status" value="Izin">
                        <input type="hidden" name="notes" value="Izin via sistem mandiri">
                        <button type="submit" class="w-full bg-surface text-on-surface rounded-xl py-4 flex flex-col items-center justify-center gap-1 hover:bg-surface-container-high transition-all hover:scale-[1.02] active:scale-[0.98] border shadow-sm group">
                            <span class="material-symbols-outlined text-[32px] group-hover:-translate-y-1 transition-transform text-orange-600">event_note</span>
                            <span class="font-headline font-bold text-lg text-slate-800">Ajukan Izin</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('member.attendance.store') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="status" value="Sakit">
                        <input type="hidden" name="notes" value="Sakit via sistem mandiri">
                        <button type="submit" class="w-full bg-surface text-on-surface rounded-xl py-4 flex flex-col items-center justify-center gap-1 hover:bg-surface-container-high transition-all hover:scale-[1.02] active:scale-[0.98] border shadow-sm group">
                            <span class="material-symbols-outlined text-[32px] group-hover:-translate-y-1 transition-transform text-red-600">sick</span>
                            <span class="font-headline font-bold text-lg text-slate-800">Sakit</span>
                        </button>
                    </form>
                </div>
                @endif
            @else
            <div class="text-center">
                <span class="material-symbols-outlined text-[64px] text-slate-300 mb-4">event_busy</span>
                <h2 class="font-headline text-2xl font-bold text-slate-500">Tidak ada Sesi Aktif</h2>
                <p class="text-slate-400 text-sm mt-2">Belum ada latihan yang berlangsung saat ini. Harap tunggu admin membukanya.</p>
            </div>
            @endif
        </section>
    </div>
    
    <!-- Secondary Info Column (Session Details & Status) -->
    <div class="lg:col-span-5 flex flex-col gap-8">
        <!-- Session Details Card -->
        <section class="bg-surface-container-lowest rounded-[1.5rem] p-6 lg:p-8 relative shadow-sm border border-slate-100 bg-white">
            <!-- Gold Thread Accent -->
            <div class="absolute left-0 top-6 bottom-6 w-1 bg-tertiary-container rounded-r-full"></div>
            
            <h2 class="font-headline text-xl font-semibold text-on-surface mb-6 pl-4">Sesi Saat Ini</h2>
            
            @if($schedule)
            <div class="flex flex-col gap-5 pl-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0 text-secondary bg-slate-100">
                        <span class="material-symbols-outlined">music_note</span>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1 text-xs font-bold text-slate-500">Nama Sesi</p>
                        <p class="font-headline text-lg font-medium text-on-surface text-slate-900">{{ $schedule->title }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0 text-secondary bg-slate-100">
                        <span class="material-symbols-outlined">event</span>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1 text-xs font-bold text-slate-500">Tanggal &amp; Waktu</p>
                        <p class="font-headline text-lg font-medium text-on-surface text-slate-900">{{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</p>
                        <p class="text-on-surface-variant text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                            @if($schedule->end_time) - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} @endif
                            WITA
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0 text-secondary bg-slate-100">
                        <span class="material-symbols-outlined">apartment</span>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1 text-xs font-bold text-slate-500">Lokasi</p>
                        <p class="font-headline text-lg font-medium text-on-surface text-slate-900">{{ $schedule->location ?? 'Belum Ditentukan' }}</p>
                        <p class="text-on-surface-variant text-sm text-slate-600">{{ $schedule->description }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="pl-4 text-slate-500 italic">
                Sesi latihan belum tersedia.
            </div>
            @endif
        </section>
        
        <!-- Check-in Status -->
        <section class="rounded-[1.5rem] p-6 text-center border {{ isset($sessionExpired) && $sessionExpired && !$alreadyCheckedIn ? 'bg-red-50 border-red-100' : 'bg-surface-container-low border-slate-100' }}">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-2 font-bold text-slate-500 text-xs">Status Absen Anda</p>
            @if($alreadyCheckedIn)
            <div class="inline-flex items-center gap-2 {{ (isset($attendanceRecord) && $attendanceRecord->status === 'Terlambat') ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }} px-4 py-2 rounded-full font-medium shadow-sm">
                <span class="material-symbols-outlined text-[18px]">{{ (isset($attendanceRecord) && $attendanceRecord->status === 'Terlambat') ? 'warning' : 'check_circle' }}</span>
                Sudah Terisi ({{ isset($attendanceRecord) ? $attendanceRecord->status : 'Hadir' }})
            </div>
            @elseif(isset($sessionExpired) && $sessionExpired)
            {{-- Sesi sudah selesai, anggota tidak presensi --}}
            <div class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-full font-medium shadow-sm">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">event_busy</span>
                Sesi Selesai
            </div>
            <p class="text-xs text-red-400 mt-3">Anda tidak melakukan presensi pada sesi ini.</p>
            @else
            <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full font-medium shadow-sm">
                <span class="material-symbols-outlined text-[18px]">hourglass_empty</span>
                Belum Terisi
            </div>
            <p class="text-xs text-on-surface-variant mt-4 text-slate-500">Pilih salah satu status di atas sebelum waktu check-in berakhir.</p>
            @endif
        </section>
    </div>
</div>
@endsection
