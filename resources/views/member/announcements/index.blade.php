@extends('layouts.app')

@section('content')
<div class="px-6 py-8 md:px-12 md:py-10 animate-fade-in-up">
    <div class="mb-8">
        <h1 class="text-3xl font-bold font-['Plus_Jakarta_Sans'] text-blue-950 dark:text-white">Papan Informasi</h1>
        <p class="text-slate-500 mt-1 dark:text-slate-400">Pengumuman dan Peraturan seputar Paduan Suara UNDIPA.</p>
    </div>

    <div class="space-y-6">
        @forelse($announcements as $item)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="absolute left-0 top-0 bottom-0 w-2 {{ $item->type == 'peraturan' ? 'bg-orange-500' : 'bg-blue-500' }}"></div>
            
            <div class="p-6 md:p-8 pl-8 md:pl-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ $item->type == 'peraturan' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} uppercase tracking-wider">{{ ucfirst($item->type) }}</span>
                    <span class="text-sm text-slate-500">{{ $item->created_at->translatedFormat('d F Y') }}</span>
                </div>
                
                <h2 class="text-xl md:text-2xl font-bold text-slate-800 mb-4">{{ $item->title }}</h2>
                
                <div class="prose prose-slate max-w-none text-slate-700 mb-6">
                    {!! $item->content !!}
                </div>
                
                @if($item->attachment_path)
                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('announcements.download', $item->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors rounded-xl text-sm font-bold">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Unduh Lampiran: {{ $item->attachment_name }}
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-4">inbox</span>
            <h3 class="text-xl font-bold text-slate-700">Belum Ada Informasi</h3>
            <p class="text-slate-500 mt-2">Tidak ada pengumuman atau peraturan untuk saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
