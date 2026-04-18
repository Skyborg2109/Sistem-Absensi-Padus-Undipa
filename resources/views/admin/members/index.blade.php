@extends('layouts.app')

@section('title', 'Manajemen Anggota - The Orchestrated Ledger')

@section('content')
<!-- Page Header: Editorial Authority -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div class="max-w-2xl">
        <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-primary tracking-tight leading-tight mb-2">Direktori Anggota</h2>
        <p class="font-body text-on-surface-variant text-base">Kelola daftar tim dan penugasan bagian.</p>
    </div>
    <!-- Conductor Action -->
    <button onclick="document.getElementById('createMemberModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-semibold text-sm tracking-wide shadow-[0_16px_32px_rgba(0,10,30,0.06)] hover:shadow-[0_8px_24px_rgba(0,10,30,0.1)] transition-shadow duration-300 self-start md:self-end">
        <span class="material-symbols-outlined text-base">add</span>
        Tambah Anggota
    </button>
</div>

@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Controls Area: Search & Filters inside Tonal Layer -->
<form method="GET" action="{{ route('admin.members.index') }}" class="bg-white dark:bg-slate-900 rounded-[1.5rem] shadow-sm p-4 lg:p-6 mb-6 flex flex-col lg:flex-row gap-4 items-center">
    <!-- Search Input -->
    <div class="relative w-full lg:w-96">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
        <input name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="w-full bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-white placeholder:text-on-surface-variant pl-12 pr-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-blue-500 focus:outline-none font-body text-sm transition-all" placeholder="Cari anggota berdasarkan nama atau nomor telepon..." type="text"/>
    </div>
    <!-- Voice Type Filters -->
    <input type="hidden" name="voice" id="voice_filter" value="{{ request('voice', 'Semua Suara') }}">
    <div class="flex gap-2 overflow-x-auto w-full pb-2 lg:pb-0 scrollbar-hide">
        @php
            $voices = ['Semua Suara', 'Soprano', 'Alto', 'Tenor', 'Bass'];
            $currentVoice = request('voice', 'Semua Suara');
        @endphp
        @foreach($voices as $v)
            <button type="button" onclick="document.getElementById('voice_filter').value='{{ $v }}'; this.form.submit();" 
                class="whitespace-nowrap px-5 py-2.5 rounded-full font-headline text-sm transition-colors {{ $currentVoice === $v ? 'bg-blue-950 text-white font-semibold shadow-sm' : 'bg-surface text-on-surface hover:bg-surface-container-high font-medium' }}">
                {{ $v }}
            </button>
        @endforeach
    </div>
</form>

<!-- Table/List Area: Asymmetrical Card List (No Vertical Dividers) -->
<div class="bg-white dark:bg-slate-900 rounded-[1.5rem] shadow-sm flex flex-col p-2 lg:p-4">
    <!-- Header Pseudo-Row -->
    <div class="hidden md:grid grid-cols-12 gap-6 px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <div class="col-span-5 font-headline text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nama</div>
        <div class="col-span-3 font-headline text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Jenis Suara</div>
        <div class="col-span-3 font-headline text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nomor Telepon</div>
        <div class="col-span-1 font-headline text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-right">Aksi</div>
    </div>
    <div class="flex flex-col gap-2 mt-2">
        @forelse($members as $member)
        <div class="bg-surface hover:bg-surface-container-low rounded-xl p-4 md:px-6 md:py-4 grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 items-center relative overflow-hidden group transition-colors">
            @if($member->status_anggota === 'Aktif')
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-tertiary-container"></div>
            @endif
            <div class="col-span-1 md:col-span-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center font-headline font-bold text-primary">
                    {{ substr($member->name, 0, 2) }}
                </div>
                <div class="flex flex-col">
                    <span class="font-headline font-semibold text-on-surface text-base">{{ $member->name }}</span>
                    <span class="font-body text-xs text-on-surface-variant md:hidden">{{ $member->voice_part ?? 'Belum Ditentukan' }}</span>
                </div>
            </div>
            <div class="hidden md:flex col-span-3 items-center">
                <span class="inline-flex px-3 py-1 bg-primary-fixed text-on-primary-fixed-variant rounded-md font-body text-xs font-medium tracking-wide">
                    {{ $member->voice_part ?? 'Belum Ditentukan' }}
                </span>
            </div>
            <div class="hidden md:block col-span-3 font-body text-sm text-on-surface-variant">
                {{ $member->phone ?? '-' }}
            </div>
            <div class="absolute right-4 top-4 md:relative md:right-0 md:top-0 md:col-span-1 flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity focus-within:opacity-100">
                <button onclick="openEditMemberModal({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->email) }}', '{{ addslashes($member->nim) }}', '{{ addslashes($member->voice_part) }}', '{{ addslashes($member->phone) }}')" class="p-2 text-secondary hover:text-tertiary hover:bg-tertiary-fixed/20 rounded-lg transition-colors focus:opacity-100" title="Edit Anggota">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </button>
                <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Seluruh data presensi atas nama anggota akan terhapus.');" class="inline-block focus:opacity-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-secondary hover:text-error hover:bg-error-container/50 rounded-lg transition-colors" title="Hapus Anggota">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-on-surface-variant">
            Pencarian Anda belum membuahkan hasil.
        </div>
        @endforelse
    </div>
</div>
<!-- Bottom spacing for scroll -->
<div class="h-20"></div>

<!-- Modals -->
<!-- Create Member Modal -->
<div id="createMemberModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('createMemberModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.members.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                        Tambah Anggota Baru
                    </h3>
                    <div class="space-y-4 font-body">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">STB / NIM</label>
                                <input type="text" name="nim" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Suara</label>
                                <select name="voice_part" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="" selected disabled>Pilih Suara</option>
                                    <option value="Soprano 1">Soprano 1</option>
                                    <option value="Soprano 2">Soprano 2</option>
                                    <option value="Alto 1">Alto 1</option>
                                    <option value="Alto 2">Alto 2</option>
                                    <option value="Tenor 1">Tenor 1</option>
                                    <option value="Tenor 2">Tenor 2</option>
                                    <option value="Bass 1">Bass 1</option>
                                    <option value="Bass 2">Bass 2</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Minimal 8 karakter">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('createMemberModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"> Batal </button>
                    <button type="submit" class="px-4 py-2 bg-blue-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-800 focus:outline-none"> Daftar </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('editMemberModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="editMemberForm" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                        Edit Data Anggota
                    </h3>
                    <div class="space-y-4 font-body">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">STB / NIM</label>
                                <input type="text" name="nim" id="edit_nim" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Suara</label>
                                <select name="voice_part" id="edit_voice_part" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="" disabled>Pilih Suara</option>
                                    <option value="Soprano 1">Soprano 1</option>
                                    <option value="Soprano 2">Soprano 2</option>
                                    <option value="Alto 1">Alto 1</option>
                                    <option value="Alto 2">Alto 2</option>
                                    <option value="Tenor 1">Tenor 1</option>
                                    <option value="Tenor 2">Tenor 2</option>
                                    <option value="Bass 1">Bass 1</option>
                                    <option value="Bass 2">Bass 2</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" id="edit_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('editMemberModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"> Batal </button>
                    <button type="submit" class="px-4 py-2 bg-blue-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-800 focus:outline-none"> Simpan Perubahan </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditMemberModal(id, name, email, nim, voice_part, phone) {
        document.getElementById('editMemberForm').action = "/admin/members/" + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_nim').value = nim;
        document.getElementById('edit_voice_part').value = voice_part;
        document.getElementById('edit_phone').value = phone;
        
        document.getElementById('editMemberModal').classList.remove('hidden');
    }
</script>
@endsection
