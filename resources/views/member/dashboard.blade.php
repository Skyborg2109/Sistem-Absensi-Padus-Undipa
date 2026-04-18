@extends('layouts.app')

@section('title', 'Dipa Choir - Member Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-12 max-w-5xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h2 class="font-headline text-display-lg font-bold text-on-surface tracking-tight leading-tight text-4xl">Selamat datang,<br/>{{ $user->name }}.</h2>
        <p class="font-body text-body-md text-on-surface-variant mt-2 max-w-md">Kehadiran vokal Anda membentuk harmoni. Tinjau jadwal Anda yang akan datang dan partisipasi terbaru.</p>
    </div>
    <!-- Mobile Check-in CTA (Visible only on mobile since sidebar has it on web) -->
    <a href="{{ url('/member/attendance/check-in') }}" class="md:hidden w-full sm:w-auto gradient-cta text-on-primary rounded-full py-3 px-6 flex items-center justify-center gap-2 font-headline font-semibold text-sm hover:opacity-90 transition-opacity shadow-[0_4px_32px_rgba(0,10,30,0.15)] bg-gradient-to-br from-primary to-primary-container">
        <span class="material-symbols-outlined text-tertiary-fixed">how_to_reg</span>
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
    <div class="md:col-span-8 bg-surface-container-lowest rounded-xl p-8 relative overflow-hidden flex flex-col justify-between">
        <!-- Decor element -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-tertiary-container"></div>
        
        @if($nextSchedule)
        <div>
            <div class="flex items-center gap-2 text-on-surface-variant mb-4 font-label text-sm uppercase tracking-wider font-semibold">
                <span class="material-symbols-outlined text-tertiary">schedule</span>
                Latihan Berikutnya
            </div>
            <h3 class="font-headline text-headline-md font-bold text-on-surface mb-2 text-2xl">{{ $nextSchedule->title }}</h3>
            <p class="font-body text-body-md text-on-surface-variant mb-6">{{ $nextSchedule->location ?? 'Belum ditentukan' }}</p>
        </div>
        
        <div class="flex flex-wrap items-end justify-between gap-4 mt-8">
            <div class="flex gap-4">
                <div class="bg-surface-container-low px-4 py-3 rounded-lg text-center min-w-[4.5rem]">
                    <span class="block font-headline font-bold text-title-lg text-primary">{{ \Carbon\Carbon::parse($nextSchedule->date)->format('d M') }}</span>
                    <span class="block font-label text-xs text-on-surface-variant uppercase mt-1">TGL</span>
                </div>
                <div class="bg-surface-container-low px-4 py-3 rounded-lg text-center min-w-[4.5rem]">
                    <span class="block font-headline font-bold text-title-lg text-primary">{{ \Carbon\Carbon::parse($nextSchedule->time)->format('H:i') }}</span>
                    <span class="block font-label text-xs text-on-surface-variant uppercase mt-1">WAKTU</span>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="font-label text-sm text-on-surface-variant mb-1">Status Kehadiran</span>
                @php
                    $presence = \App\Models\Attendance::where('user_id', auth()->id())->where('schedule_id', $nextSchedule->id)->first();
                @endphp
                @if($presence)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary text-on-primary gap-1">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        {{ $presence->status }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-tertiary-fixed text-on-tertiary-fixed-variant gap-1">
                        <span class="material-symbols-outlined text-[14px]">pending_actions</span>
                        Belum Presensi
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-full opacity-50 py-10">
            <span class="material-symbols-outlined text-5xl mb-4">event_busy</span>
            <p class="font-headline font-bold text-xl">Tidak ada jadwal latihan</p>
            <p class="font-body text-sm mt-2">Duduk kencangkan sabuk pengaman, admin akan segera membuatnya.</p>
        </div>
        @endif
    </div>
    
    <!-- Personal Stats Card (Spans 4 cols) -->
    <div class="md:col-span-4 bg-surface-container-lowest rounded-xl p-8 flex flex-col justify-between shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center gap-2 text-on-surface-variant mb-4 font-label text-sm uppercase tracking-wider font-semibold">
                <span class="material-symbols-outlined text-primary">analytics</span>
                Tingkat Kehadiran
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-center items-center py-6">
            <!-- Radial Progress visual representation -->
            <div class="relative w-32 h-32 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewbox="0 0 100 100">
                    <circle class="text-surface-container-high" cx="50" cy="50" fill="none" r="45" stroke="currentColor" stroke-width="8"></circle>
                    <circle class="text-primary" cx="50" cy="50" fill="none" r="45" stroke="currentColor" stroke-dasharray="282.7" stroke-dashoffset="33.9" stroke-width="8"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-headline font-bold text-3xl text-primary">88%</span>
                </div>
            </div>
            <p class="font-body text-sm text-on-surface-variant mt-4 text-center">Status baik. Kejar ke 90% untuk dapat seleksi solo.</p>
        </div>
    </div>
    
    <!-- Recent Activity (Spans 12 cols) -->
    <div class="md:col-span-12 bg-surface-container-lowest rounded-xl p-8 mt-2 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline text-xl font-bold text-on-surface">Latihan Terkini</h3>
            <a class="text-sm font-semibold text-tertiary hover:text-tertiary-container transition-colors" href="{{ route('member.attendance.history') }}">Lihat Semua Riwayat</a>
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
