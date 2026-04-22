@extends('layouts.app')

@section('title', 'Dipa Choir - Member Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-10 max-w-5xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <p class="text-xs font-bold text-undipa-gold uppercase tracking-[0.2em] mb-1">Dashboard Anggota</p>
        <h2 class="font-headline font-bold text-undipa-navy tracking-tight leading-tight text-4xl">Selamat datang,<br/>{{ $user->name }}.</h2>
        <p class="font-body text-on-surface-variant mt-2 max-w-md">Kehadiran vokal Anda membentuk harmoni. Tinjau jadwal Anda yang akan datang dan partisipasi terbaru.</p>
    </div>
    <!-- Mobile Check-in CTA -->
    <a href="{{ url('/member/attendance/check-in') }}" class="md:hidden w-full sm:w-auto undipa-cta-btn rounded-full py-3 px-6 flex items-center justify-center gap-2 font-headline font-bold text-sm shadow-xl">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">how_to_reg</span>
        Check-in Sekarang
    </a>
</div>

@if(isset($latestAnnouncements) && $latestAnnouncements->count() > 0)
<!-- Latest Announcements -->
<div class="max-w-5xl mx-auto mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline text-lg font-bold text-on-surface">Informasi Terbaru</h3>
        <a class="text-sm font-semibold text-tertiary hover:text-tertiary-container transition-colors" href="{{ route('member.announcements.index') }}">Buka Semua</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($latestAnnouncements as $item)
        <a href="{{ route('member.announcements.index') }}" class="block w-full bg-white rounded-xl py-4 px-5 hover:bg-slate-50 transition-colors relative overflow-hidden group shadow-sm border border-slate-100">
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $item->type == 'peraturan' ? 'bg-orange-500' : 'bg-blue-500' }}"></div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $item->type == 'peraturan' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} uppercase tracking-wider">{{ ucfirst($item->type) }}</span>
                <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
            </div>
            <h4 class="font-bold text-slate-800 text-base mb-1 group-hover:text-blue-700 transition-colors">{{ $item->title }}</h4>
            <div class="text-sm text-slate-500 line-clamp-2">{!! strip_tags($item->content) !!}</div>
            @if($item->attachment_name)
                <div class="mt-3 flex items-center gap-1 text-xs text-blue-600 font-bold">
                    <span class="material-symbols-outlined text-[14px]">attach_file</span> Tersedia {{ $item->attachment_name }}
                </div>
            @endif
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Bento Grid Layout -->
<div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-6">
    <!-- Next Rehearsal Card (Spans 8 cols) -->
    <div class="md:col-span-8 bg-undipa-navy rounded-2xl p-8 relative overflow-hidden flex flex-col justify-between shadow-xl shadow-undipa-navy/30">
        <!-- Decor element -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-undipa-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 top-0 bottom-0 w-1.5 gold-accent-bar rounded-l-2xl"></div>
        
        @if($nextSchedule)
        @php
            $now = \Carbon\Carbon::now();
            $startTime = \Carbon\Carbon::parse($nextSchedule->date . ' ' . $nextSchedule->time);
            $endTime = $startTime->copy()->addHours(2); // Durasi diasumsikan 2 jam
            
            $statusLabel = 'Latihan Berikutnya';
            $statusIcon = 'schedule';
            $statusColor = 'text-undipa-gold';
            
            if ($now->greaterThanOrEqualTo($startTime) && $now->lessThanOrEqualTo($endTime)) {
                $statusLabel = 'Sesi Berlangsung';
                $statusIcon = 'sync';
                $statusColor = 'text-green-400';
            } elseif ($now->greaterThan($endTime) && $now->isSameDay($startTime)) {
                $statusLabel = 'Sesi Selesai';
                $statusIcon = 'task_alt';
                $statusColor = 'text-slate-300';
            }
        @endphp
        <div>
            <div class="flex items-center gap-2 {{ $statusColor }} mb-4 font-label text-xs uppercase tracking-[0.15em] font-bold">
                <span class="material-symbols-outlined text-[18px] {{ $statusIcon == 'sync' ? 'animate-spin' : '' }}" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
                {{ $statusLabel }}
            </div>
            <h3 class="font-headline font-bold text-white mb-2 text-2xl">{{ $nextSchedule->title }}</h3>
            <p class="font-body text-blue-200 mb-6">{{ $nextSchedule->location ?? 'Belum ditentukan' }}</p>
        </div>
        
        <div class="flex flex-wrap items-end justify-between gap-4 mt-8">
            <div class="flex gap-3">
                <div class="bg-white/10 border border-undipa-gold/20 px-4 py-3 rounded-xl text-center min-w-[4.5rem]">
                    <span class="block font-headline font-extrabold text-undipa-gold">{{ \Carbon\Carbon::parse($nextSchedule->date)->format('d M') }}</span>
                    <span class="block font-label text-[10px] text-blue-200 uppercase mt-1 tracking-wider">TGL</span>
                </div>
                <div class="bg-white/10 border border-undipa-gold/20 px-4 py-3 rounded-xl text-center min-w-[4.5rem]">
                    <span class="block font-headline font-extrabold text-undipa-gold">{{ \Carbon\Carbon::parse($nextSchedule->time)->format('H:i') }}</span>
                    <span class="block font-label text-[10px] text-blue-200 uppercase mt-1 tracking-wider">WAKTU</span>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="font-label text-xs text-blue-200 mb-1.5 uppercase tracking-wider">Status Kehadiran</span>
                @php
                    $presence = \App\Models\Attendance::where('user_id', auth()->id())->where('schedule_id', $nextSchedule->id)->first();
                @endphp
                @if($presence)
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold undipa-cta-btn gap-1.5">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        {{ $presence->status }}
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-white/15 text-white border border-white/20 gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">pending_actions</span>
                        Belum Presensi
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-full opacity-60 py-10">
            <span class="material-symbols-outlined text-5xl mb-4 text-undipa-gold">event_busy</span>
            <p class="font-headline font-bold text-xl text-white">Tidak ada jadwal latihan</p>
            <p class="font-body text-sm mt-2 text-blue-200">Admin akan segera membuatnya.</p>
        </div>
        @endif
    </div>
    
    <!-- Personal Stats Card (Spans 4 cols) -->
    <div class="md:col-span-4 bg-white rounded-2xl p-8 flex flex-col justify-between stat-card-glow border border-blue-50">
        <div>
            <div class="flex items-center gap-2 mb-4 font-label text-xs uppercase tracking-[0.15em] font-bold">
                <span class="material-symbols-outlined text-undipa-gold text-[18px]" style="font-variation-settings: 'FILL' 1;">analytics</span>
                <span class="text-undipa-navy">Tingkat Kehadiran</span>
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-center items-center py-4">
            <!-- Radial Progress with UNDIPA colors -->
            <div class="relative w-36 h-36 flex items-center justify-center">
                @php
                    $dasharray = 282.7;
                    $dashoffset = $dasharray - ($dasharray * ($stats['percentage'] / 100));
                @endphp
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <!-- Background track -->
                    <circle cx="50" cy="50" fill="none" r="45" stroke="#dce2f0" stroke-width="9"></circle>
                    <!-- Progress arc (Hadir + Terlambat) -->
                    <circle cx="50" cy="50" fill="none" r="45" stroke="url(#goldGradient)" stroke-dasharray="{{ $dasharray }}" stroke-dashoffset="{{ $dashoffset }}" stroke-width="9" stroke-linecap="round" class="transition-all duration-1000"></circle>
                    <defs>
                        <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#F0D060"/>
                            <stop offset="100%" stop-color="#C8A820"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-headline font-extrabold text-3xl text-undipa-navy">{{ $stats['percentage'] }}%</span>
                    <span class="text-[10px] font-bold text-undipa-gold uppercase tracking-wider">Kehadiran</span>
                </div>
            </div>
            <p class="font-body text-xs text-on-surface-variant mt-3 text-center">
                @if($stats['percentage'] >= 90)
                    Luar biasa! Pertahankan performa vokal Anda.
                @elseif($stats['percentage'] >= 75)
                    Status baik. Sedikit lagi menuju kehadiran prima.
                @else
                    Tingkatkan kehadiran Anda untuk sesi berikutnya.
                @endif
            </p>
            <!-- Real-time Stats Summary -->
            <div class="mt-5 w-full grid grid-cols-3 divide-x divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                <div class="flex flex-col items-center py-2 px-1 bg-green-50">
                    <span class="font-headline font-extrabold text-lg text-green-700">{{ $stats['hadir_count'] }}</span>
                    <span class="text-[9px] font-bold text-green-600 uppercase tracking-wider">Hadir</span>
                </div>
                <div class="flex flex-col items-center py-2 px-1 bg-slate-50">
                    <span class="font-headline font-extrabold text-lg text-slate-700">{{ $stats['total_sesi'] }}</span>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total</span>
                </div>
                <div class="flex flex-col items-center py-2 px-1 bg-red-50">
                    <span class="font-headline font-extrabold text-lg text-red-600">{{ $stats['total_sesi'] - $stats['hadir_count'] }}</span>
                    <span class="text-[9px] font-bold text-red-500 uppercase tracking-wider">Absen</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity (Spans 12 cols) -->
    <div class="md:col-span-12 bg-white rounded-2xl p-8 mt-2 stat-card-glow border border-blue-50">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 gold-accent-bar rounded-full"></div>
                <h3 class="font-headline text-xl font-bold text-undipa-navy">Latihan Terkini</h3>
            </div>
            <a class="text-sm font-bold text-undipa-navy hover:text-undipa-gold transition-colors border border-undipa-navy/20 px-3 py-1.5 rounded-full hover:bg-undipa-navy hover:text-white" href="{{ route('member.attendance.history') }}">Lihat Semua →</a>
        </div>
        <div class="flex flex-col gap-3">
            @forelse($recentAttendances as $attendance)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl bg-surface-container-low/50 hover:bg-surface-container-low transition-colors">
                <div class="flex items-center gap-4 mb-3 sm:mb-0">
                    <div class="w-12 h-12 rounded-lg bg-surface flex items-center justify-center text-on-surface-variant shrink-0">
                        <span class="font-headline font-bold text-sm">{{ \Carbon\Carbon::parse($attendance->schedule->date)->format('d M') }}</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-semibold text-on-surface">{{ $attendance->schedule->title }}</h4>
                        <p class="font-body text-sm text-on-surface-variant">{{ \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') }} | {{ $attendance->schedule->location }}</p>
                    </div>
                </div>
                @if($attendance->status === 'Hadir')
                <span class="inline-flex w-max items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 shadow-sm text-green-700 border border-green-200 gap-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    Hadir
                </span>
                @else
                <span class="inline-flex w-max items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200 gap-1 shadow-sm">
                    <span class="material-symbols-outlined text-[14px]">event_note</span>
                    {{ $attendance->status }}
                </span>
                @endif
            </div>
            @empty
            <div class="p-6 text-center text-on-surface-variant opacity-75">
                Belum ada riwayat presensi.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
