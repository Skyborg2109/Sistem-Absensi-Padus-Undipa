@extends('layouts.app')

@section('title', 'Absensi - The Orchestrated Ledger')

@section('content')
<!-- Page Header -->
<header class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 dark:text-white mb-2 font-['Plus_Jakarta_Sans']">Panggilan Sesi</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base max-w-2xl font-medium">Catat kehadiran untuk latihan penampilan mendatang. Ketepatan dalam pencatatan memastikan keharmonisan di atas panggung.</p>
    </div>
    <div class="flex gap-4 items-center">
        <div class="relative">
            <select id="schedule-select" onchange="window.location.href='?schedule_id='+this.value" class="appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer w-[250px] truncate">
                @if($schedules->isEmpty())
                    <option disabled selected>Belum ada jadwal</option>
                @else
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ $selectedSchedule && $selectedSchedule->id == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->title }} ({{ \Carbon\Carbon::parse($schedule->date)->format('d M') }})
                        </option>
                    @endforeach
                @endif
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
        </div>
        <a href="{{ route('admin.attendance.export') }}" class="bg-blue-950 text-white rounded-full px-6 py-3 font-['Plus_Jakarta_Sans'] font-semibold flex items-center gap-2 hover:bg-blue-900 transition-all shadow-sm text-sm">
            <span class="material-symbols-outlined" data-icon="download">download</span>
            Download Rekap (.csv)
        </a>
    </div>
</header>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute w-1 h-full bg-slate-200 left-0 top-0"></div>
        <h3 class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Total Diharapkan</h3>
        <p class="text-2xl font-['Plus_Jakarta_Sans'] font-bold text-slate-900">{{ $stats['expected'] }} <span class="text-sm font-medium text-slate-500 ml-1">Anggota</span></p>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute w-1 h-full bg-green-500 left-0 top-0"></div>
        <h3 class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Hadir / Terlambat</h3>
        <p class="text-2xl font-['Plus_Jakarta_Sans'] font-bold text-slate-900">{{ $stats['present'] }} <span class="text-sm font-medium text-slate-500 ml-1">Ditandai</span></p>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute w-1 h-full bg-amber-500 left-0 top-0"></div>
        <h3 class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Alpha / Tersisa</h3>
        <p class="text-2xl font-['Plus_Jakarta_Sans'] font-bold text-slate-900">{{ $stats['alpha'] }} <span class="text-sm font-medium text-slate-500 ml-1">Anggota</span></p>
    </div>
</div>

<!-- Attendance List Canvas -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
    <div class="flex justify-between items-center mb-6 px-2">
        <h3 class="text-lg font-['Plus_Jakarta_Sans'] font-bold text-slate-900">Daftar Kehadiran Vokal</h3>
        <div class="flex gap-2">
            <span class="text-xs font-semibold text-slate-400 italic">Klik status untuk mengubah secara manual</span>
        </div>
    </div>
    <!-- List Header (Glassy) -->
    <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 mb-2 bg-slate-50 rounded-xl text-xs font-bold text-slate-500 uppercase tracking-wider border border-slate-100">
        <div class="col-span-1">No.</div>
        <div class="col-span-4">Nama Vokalis</div>
        <div class="col-span-2">Bagian Suara</div>
        <div class="col-span-5 text-right">Aksi Status</div>
    </div>
    <!-- Member Rows (Asymmetric & Spaced) -->
    <div class="flex flex-col gap-3">
        @if(!$selectedSchedule)
            <div class="p-8 text-center text-slate-500">
                Pilih jadwal terlebih dahulu.
            </div>
        @else
            @foreach($members as $index => $member)
                @php
                    $attendance = $attendances->get($member->id);
                    $status = $attendance ? $attendance->status : null;
                @endphp
                <div class="bg-white rounded-xl p-4 md:px-6 flex flex-col md:grid md:grid-cols-12 gap-4 items-center shadow-sm border border-slate-100 relative group hover:bg-slate-50 transition-colors">
                    @if($status === 'Hadir')
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-green-500 rounded-r-full hidden md:block"></div>
                    @elseif($status === 'Alpha')
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-red-500 rounded-r-full hidden md:block"></div>
                    @elseif($status === 'Terlambat')
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-amber-500 rounded-r-full hidden md:block"></div>
                    @elseif(in_array($status, ['Izin', 'Sakit']))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-amber-400 rounded-r-full hidden md:block"></div>
                    @else
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-200 rounded-r-full hidden md:block"></div>
                    @endif

                    <div class="md:col-span-1 text-sm font-medium text-slate-500 hidden md:block">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="md:col-span-4 flex items-center gap-4 w-full md:w-auto justify-between md:justify-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full shadow-sm bg-slate-200 flex items-center justify-center font-bold text-slate-500">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $member->name }}</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-medium text-slate-500 md:hidden">{{ $member->voice_part ?? 'Soprano 1' }}</p>
                                    @if($attendance)
                                        <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100 font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ $attendance->created_at->format('H:i') }}
                                        </span>
                                    @endif
                                </div>
                                @if($attendance && $attendance->notes)
                                    <p class="text-[10px] text-slate-400 italic mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">notes</span>
                                        "{{ $attendance->notes }}"
                                    </p>
                                @endif
                                @if($attendance && ($attendance->image_path || ($attendance->latitude && $attendance->longitude)))
                                    <div class="flex items-center gap-2 mt-1.5 border-t border-slate-100 pt-1">
                                        @if($attendance->image_path)
                                            <a href="{{ str_starts_with($attendance->image_path, 'http') ? $attendance->image_path : asset('storage/' . $attendance->image_path) }}" target="_blank" class="flex items-center gap-0.5 text-[9px] font-bold uppercase tracking-tighter text-blue-600 hover:text-blue-800 transition-colors" title="Lihat Foto Verifikasi">
                                                <span class="material-symbols-outlined text-[12px]">image</span>
                                                Foto
                                            </a>
                                        @endif
                                        @if($attendance->latitude && $attendance->longitude)
                                            <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank" class="flex items-center gap-0.5 text-[9px] font-bold uppercase tracking-tighter text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Lokasi GPS">
                                                <span class="material-symbols-outlined text-[12px]">location_on</span>
                                                Peta
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2 text-sm text-slate-500 hidden md:block">
                        <span class="bg-slate-100 px-3 py-1 rounded-full text-xs font-medium">{{ $member->voice_part ?? 'Soprano 1' }}</span>
                    </div>
                    <div class="md:col-span-5 w-full md:w-auto flex justify-end gap-1.5 overflow-x-auto pb-1 md:pb-0">
                        <form action="{{ route('admin.attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                            <input type="hidden" name="status" value="Hadir">
                            <button class="px-3 py-1.5 rounded-full text-[11px] font-bold transition-colors flex items-center justify-center gap-1 {{ $status === 'Hadir' ? 'bg-green-100 text-green-800 border-transparent' : 'border border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                Hadir
                            </button>
                        </form>
                        <form action="{{ route('admin.attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                            <input type="hidden" name="status" value="Terlambat">
                            <button class="px-3 py-1.5 rounded-full text-[11px] font-bold transition-colors flex items-center justify-center gap-1 {{ $status === 'Terlambat' ? 'bg-amber-100 text-amber-800 border-transparent' : 'border border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                Telat
                            </button>
                        </form>
                        <form action="{{ route('admin.attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                            <input type="hidden" name="status" value="Izin">
                            <button class="px-3 py-1.5 rounded-full text-[11px] font-bold transition-colors flex items-center justify-center gap-1 {{ in_array($status, ['Izin', 'Sakit']) ? 'bg-orange-50 text-orange-700 border-transparent' : 'border border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                                Izin
                            </button>
                        </form>
                        <form action="{{ route('admin.attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                            <input type="hidden" name="status" value="Alpha">
                            <button class="px-3 py-1.5 rounded-full text-[11px] font-bold transition-colors flex items-center justify-center gap-1 {{ $status === 'Alpha' ? 'bg-red-50 text-red-700 border-transparent' : 'border border-slate-200 hover:bg-slate-50 text-slate-600' }}">
                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                                Alpha
                            </button>
                        </form>
                        @if($status)
                        <form action="{{ route('admin.attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                            <input type="hidden" name="status" value="Belum Absen">
                            <button class="px-2 py-1.5 rounded-full transition-colors flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-400">
                                <span class="material-symbols-outlined text-[16px]">replay</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
