@extends('layouts.app')

@section('content')
<div class="px-6 py-8 md:px-12 md:py-10 animate-fade-in-up">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold font-['Plus_Jakarta_Sans'] text-blue-950 dark:text-white">Papan Informasi</h1>
            <p class="text-slate-500 mt-1 dark:text-slate-400">Kelola pengumuman dan peraturan untuk anggota.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-blue-900 border border-transparent rounded-xl text-sm font-medium text-white hover:bg-blue-800 transition-colors shadow-sm focus:outline-none flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Buat Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="grid grid-cols-1 divide-y divide-slate-100">
            @forelse($announcements as $item)
            <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full {{ $item->type == 'peraturan' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">{{ $item->type == 'peraturan' ? 'gavel' : 'campaign' }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $item->type == 'peraturan' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} uppercase tracking-wider">{{ ucfirst($item->type) }}</span>
                            @if(!$item->is_active)
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase tracking-wider">Draft</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $item->title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Dibuat pada {{ $item->created_at->format('d M Y') }}</p>
                        @if($item->attachment_name)
                            <div class="flex items-center gap-1 mt-2 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-sm">attach_file</span>
                                <span>{{ $item->attachment_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="flex-1 md:flex-none justify-center px-4 py-2 border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                    </a>
                    <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" class="flex-1 md:flex-none" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full justify-center px-4 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-3">campaign</span>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Informasi</h3>
                <p class="text-slate-500 mt-1">Tambahkan pengumuman atau peraturan untuk member.</p>
            </div>
            @endforelse
        </div>
        @if($announcements->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
