@extends('layouts.app')

@section('title', 'Manajemen Jadwal Latihan')

@section('content')
<div class="max-w-[1200px] mx-auto space-y-10 mt-8 mb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-surface-container-lowest p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br from-primary-fixed/40 to-transparent blur-3xl pointer-events-none rounded-full translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="relative z-10 flex flex-col gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-fixed text-on-primary-fixed text-xs font-bold tracking-wide uppercase mb-2 w-max">
                <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                Jadwal Latihan
            </div>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold text-primary tracking-[-0.02em]">Manajemen Jadwal</h1>
            <p class="text-on-surface-variant font-body text-base mt-2 max-w-xl">Atur dan distribusikan jadwal latihan kepada semua anggota padus. Jadwal yang aktif akan muncul di dashboard anggota untuk absen.</p>
        </div>
        
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="relative z-10 w-full md:w-auto bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl py-3 px-6 flex items-center justify-center gap-2 font-headline font-semibold hover:opacity-90 transition-opacity shadow-[0_8px_16px_rgba(0,10,30,0.1)] group">
            <span class="material-symbols-outlined transition-transform group-hover:rotate-90 duration-300">add</span>
            Tambah Jadwal
        </button>
    </div>
    
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter & Sort -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="relative w-full sm:w-96">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" placeholder="Cari sesi latihan..." class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-primary focus:outline-none transition-all font-body text-sm text-on-surface" />
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <select class="w-full sm:w-auto px-4 py-3 bg-surface-container-lowest border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-primary focus:outline-none font-headline font-semibold text-sm text-on-surface cursor-pointer">
                <option>Semua Status</option>
                <option>Akan Datang</option>
                <option>Sedang Berjalan</option>
                <option>Selesai</option>
            </select>
        </div>
    </div>

    <!-- Jadwal List (Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($schedules as $schedule)
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col group hover:shadow-md transition-shadow relative overflow-hidden {{ $schedule->status === 'completed' ? 'opacity-75' : '' }}">
            <div class="absolute left-0 top-0 bottom-0 {{ $schedule->status === 'completed' ? 'w-1.5 bg-slate-300' : 'w-1.5 bg-tertiary-container transition-all group-hover:w-2' }}"></div>
            
            <div class="flex justify-between items-start mb-4">
                @if($schedule->status === 'active')
                <div class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                    Akan Datang / Berjalan
                </div>
                @else
                <div class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    Selesai
                </div>
                @endif
                
                <div class="flex gap-2">
                    <button onclick="openEditModal({{ $schedule->id }}, '{{ addslashes($schedule->title) }}', '{{ $schedule->date }}', '{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}', '{{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}', '{{ addslashes($schedule->location) }}', '{{ addslashes($schedule->description) }}', '{{ $schedule->status }}')" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit Jadwal">
                        <span class="material-symbols-outlined text-[20px]">{{ $schedule->status === 'completed' ? 'visibility' : 'edit' }}</span>
                    </button>
                    @if($schedule->status === 'active')
                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini? Data presensi yang terkait juga akan dihapus.');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Hapus Jadwal"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                    </form>
                    @endif
                </div>
            </div>
            
            <h3 class="font-headline text-2xl font-bold text-on-surface mb-2">{{ $schedule->title }}</h3>
            <p class="text-on-surface-variant text-sm font-body mb-6 line-clamp-2">{{ $schedule->description ?? 'Tidak ada deskripsi' }}</p>
            
            <div class="mt-auto grid grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[20px]">event</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal</p>
                        <p class="text-sm font-semibold text-on-surface">{{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu</p>
                        <p class="text-sm font-semibold text-on-surface">
                            {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                            @if($schedule->end_time) - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} @endif
                            WITA
                        </p>
                    </div>
                </div>
                <div class="col-span-2 flex items-center gap-3 mt-2">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lokasi</p>
                        <p class="text-sm font-semibold text-on-surface">{{ $schedule->location ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end">
                <a href="{{ route('admin.attendance.index') }}" class="text-sm font-bold text-primary hover:text-primary-container flex items-center gap-1 transition-colors">
                    {{ $schedule->status === 'completed' ? 'Lihat Rekapitulasi' : 'Kelola Presensi Sesi Ini' }} <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-1 lg:col-span-2 p-12 text-center bg-surface rounded-3xl border border-dashed border-slate-200">
            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">event_busy</span>
            <h3 class="font-headline font-bold text-slate-500">Belum ada jadwal</h3>
            <p class="text-slate-400 text-sm mt-1">Tambahkan jadwal baru untuk memulai sesi latihan.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modals -->
<!-- Create Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                Tambah Jadwal Baru
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Latihan</label>
                                    <input type="text" name="title" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                        <input type="date" name="date" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                                        <input type="time" name="time" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                                        <input type="time" name="end_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" name="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        <option value="active">Akan Datang / Berjalan</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"> Batal </button>
                    <button type="submit" class="px-4 py-2 bg-blue-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-800 focus:outline-none"> Simpan </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                Edit / Lihat Jadwal
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Latihan</label>
                                    <input type="text" name="title" id="edit_title" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                        <input type="date" name="date" id="edit_date" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                                        <input type="time" name="time" id="edit_time" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                                        <input type="time" name="end_time" id="edit_end_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" name="location" id="edit_location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="edit_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        <option value="active">Akan Datang / Berjalan</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" id="edit_description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"> Batal </button>
                    <button type="submit" class="px-4 py-2 bg-blue-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-800 focus:outline-none"> Simpan Perubahan </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, title, date, time, end_time, location, description, status) {
        document.getElementById('editForm').action = "/admin/schedules/" + id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_time').value = time;
        document.getElementById('edit_end_time').value = end_time;
        document.getElementById('edit_location').value = location;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_status').value = status;
        
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
