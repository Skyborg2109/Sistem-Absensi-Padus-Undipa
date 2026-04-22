@extends('layouts.app')

@section('title', 'Riwayat Notifikasi - Universitas Dipa Makassar Choir')

@section('content')
<div class="max-w-[800px] mx-auto space-y-8 mt-4 mb-24">
    <!-- Header -->
    <div class="flex flex-col gap-2 mb-8">
        <h1 class="font-headline text-3xl md:text-4xl font-extrabold text-primary tracking-[-0.02em]">Riwayat Notifikasi</h1>
        <p class="text-on-surface-variant font-body text-base">Tinjau semua pemberitahuan sistem dan informasi terbaru untuk Anda.</p>
    </div>

    <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $icon = $data['icon'] ?? 'notifications';
                    $type = $data['type'] ?? 'info';
                    $color = $type === 'peraturan' ? 'text-orange-500 bg-orange-50' : ($type === 'jadwal' ? 'text-blue-500 bg-blue-50' : 'text-slate-500 bg-slate-50');
                @endphp
                <div class="flex items-start gap-4 p-6 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $color }}">
                        <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-headline font-bold text-slate-900">{{ $data['title'] ?? 'Pemberitahuan' }}</h3>
                            <span class="text-xs text-slate-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $data['message'] }}</p>
                        @if(isset($data['url']))
                            <a href="{{ $data['url'] }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mt-4 hover:underline">
                                Lihat Detail
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-20 text-center flex flex-col items-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-slate-200">notifications_off</span>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada riwayat notifikasi untuk Anda.</p>
                    <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-primary hover:underline">Kembali ke Dashboard</a>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
