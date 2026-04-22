@extends('layouts.app')

@section('title', 'Pengaturan - Universitas Dipa Makassar Choir')

@section('content')
<div class="max-w-[800px] mx-auto space-y-8 mt-4 mb-24">
    <!-- Header -->
    <div class="flex flex-col gap-2 mb-8">
        <h1 class="font-headline text-3xl md:text-4xl font-extrabold text-primary tracking-[-0.02em]">Pengaturan Akun</h1>
        <p class="text-on-surface-variant font-body text-base">Kelola informasi profil dan ubah kata sandi Anda.</p>
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

    <div class="bg-surface-container-lowest rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <h2 class="text-xl font-bold font-headline text-on-surface mb-4 pb-2 border-b border-slate-100">Informasi Pribadi</h2>
            
            <!-- Avatar Upload -->
            <div class="flex items-center gap-6 mb-8">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-slate-50 shadow-md bg-slate-100 flex items-center justify-center">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-4xl text-slate-400">person</span>
                        @endif
                    </div>
                    <label for="avatar-input" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <span class="material-symbols-outlined">photo_camera</span>
                    </label>
                    <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                </div>
                <div class="flex flex-col gap-1">
                    <p class="font-bold text-slate-900">Foto Profil</p>
                    <p class="text-xs text-slate-500">Gunakan format JPG atau PNG (Maks. 2MB)</p>
                    <button type="button" onclick="document.getElementById('avatar-input').click()" class="text-xs font-bold text-primary mt-1 text-left hover:underline">Ubah Foto</button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-bold font-headline text-on-surface mb-1">Ubah Kata Sandi</h2>
                <p class="text-sm text-slate-500 mb-4 pb-2 border-b border-slate-100">Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="w-full md:w-1/2 rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                        <input type="password" name="new_password" class="w-full md:w-1/2 rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full md:w-1/2 rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl py-3 px-8 font-headline font-semibold hover:opacity-90 shadow-md transition-opacity">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = input.previousElementSibling.previousElementSibling.querySelector('img') || document.createElement('img');
                if (!input.previousElementSibling.previousElementSibling.querySelector('img')) {
                    input.previousElementSibling.previousElementSibling.innerHTML = '';
                    input.previousElementSibling.previousElementSibling.appendChild(img);
                }
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
