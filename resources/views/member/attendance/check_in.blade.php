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
                        <p class="text-on-surface-variant text-sm mt-2">
                            Anda sudah melakukan presensi untuk sesi ini ({{ isset($attendanceRecord) ? $attendanceRecord->status : 'Terekam' }}).
                            @if(isset($attendanceRecord) && !in_array($attendanceRecord->status, ['Izin', 'Sakit']))
                                Selamat berlatih!
                            @endif
                        </p>
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
                <div id="verification-section" class="mb-10 max-w-sm mx-auto">
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50">
                        <h3 class="font-headline font-bold text-slate-900 mb-4 flex items-center gap-2 text-lg">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
                            </div>
                            Verifikasi Kehadiran
                        </h3>
                        
                        <div id="camera-container" class="relative rounded-2xl overflow-hidden bg-slate-900 aspect-[4/3] mb-5 shadow-inner border border-slate-200 group">
                            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <img id="photo-preview" class="hidden w-full h-full object-cover">
                            
                            <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-slate-900 text-slate-400 flex-col gap-3">
                                <div class="w-10 h-10 border-[3px] border-primary border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em]">Menginisialisasi Kamera</p>
                            </div>

                            <div id="camera-error" class="hidden absolute inset-0 flex items-center justify-center bg-red-50 text-red-600 p-6 text-center flex-col gap-3">
                                <span class="material-symbols-outlined text-4xl">videocam_off</span>
                                <p class="text-sm font-bold leading-tight">Akses Kamera Ditolak</p>
                                <p class="text-[10px] font-medium opacity-80 leading-relaxed uppercase tracking-wider">Fitur ini wajib untuk presensi. Mohon aktifkan izin kamera di pengaturan browser.</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <button type="button" id="capture-btn" class="flex items-center justify-center gap-3 bg-primary text-white py-4 rounded-2xl hover:bg-primary/90 transition-all font-bold shadow-lg shadow-primary/20 active:scale-[0.98]">
                                <span class="material-symbols-outlined">photo_camera</span>
                                Ambil Foto Sekarang
                            </button>
                            <button type="button" id="retake-btn" class="hidden flex items-center justify-center gap-3 bg-slate-100 text-slate-700 py-4 rounded-2xl hover:bg-slate-200 transition-all font-bold border border-slate-200 active:scale-[0.98]">
                                <span class="material-symbols-outlined">refresh</span>
                                Ganti Foto
                            </button>
                            
                            <div id="location-status" class="mt-2 flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 transition-all duration-500">
                                <div class="relative flex h-3.5 w-3.5 mt-0.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75" id="location-ping"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-orange-500" id="location-dot"></span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Status Lokasi</span>
                                    <span id="location-text" class="text-xs font-bold text-slate-600 leading-tight">Mendeteksi Alamat GPS...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="font-headline text-2xl font-bold text-on-surface">Pilih Status Kehadiran</h2>
                    <p class="text-on-surface-variant text-sm mt-2">Pilih status Anda, lalu tekan tombol konfirmasi di bawah.</p>
                </div>
                
                <div class="flex flex-col gap-6 max-w-sm mx-auto w-full opacity-40 pointer-events-none transition-all duration-700 blur-[2px]" id="action-buttons">
                    <!-- Status Selection Grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" onclick="selectStatus('Hadir')" id="status-Hadir" class="status-choice p-4 rounded-2xl border-2 border-slate-100 bg-white flex flex-col items-center gap-2 transition-all hover:border-primary/30 group">
                            <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-primary transition-colors">check_circle</span>
                            <span class="text-xs font-bold text-slate-600">Hadir</span>
                        </button>
                        <button type="button" onclick="selectStatus('Izin')" id="status-Izin" class="status-choice p-4 rounded-2xl border-2 border-slate-100 bg-white flex flex-col items-center gap-2 transition-all hover:border-orange-300 group">
                            <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-orange-500 transition-colors">event_note</span>
                            <span class="text-xs font-bold text-slate-600">Izin</span>
                        </button>
                        <button type="button" onclick="selectStatus('Sakit')" id="status-Sakit" class="status-choice p-4 rounded-2xl border-2 border-slate-100 bg-white flex flex-col items-center gap-2 transition-all hover:border-red-300 group">
                            <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-red-500 transition-colors">sick</span>
                            <span class="text-xs font-bold text-slate-600">Sakit</span>
                        </button>
                    </div>

                    <form action="{{ route('member.attendance.store') }}" method="POST" class="w-full" id="attendance-main-form">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="status" id="final-status" value="">
                        <input type="hidden" name="notes" id="final-notes" value="">
                        <input type="hidden" name="image" id="final-image">
                        <input type="hidden" name="latitude" id="final-lat">
                        <input type="hidden" name="longitude" id="final-lng">
                        
                        <button type="submit" id="submit-presensi" disabled class="w-full bg-slate-200 text-slate-500 rounded-2xl py-4 font-headline font-black text-lg transition-all shadow-lg shadow-slate-100 flex items-center justify-center gap-2">
                            Absen Sekarang
                            <span class="material-symbols-outlined">arrow_forward</span>
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const cameraLoading = document.getElementById('camera-loading');
        const cameraError = document.getElementById('camera-error');
        const locationStatus = document.getElementById('location-status');
        const locationText = document.getElementById('location-text');
        const locationDot = document.getElementById('location-dot');
        const locationPing = document.getElementById('location-ping');
        const actionButtons = document.getElementById('action-buttons');
        const submitBtn = document.getElementById('submit-presensi');
        const finalStatusInput = document.getElementById('final-status');
        const finalImageInput = document.getElementById('final-image');
        const finalLatInput = document.getElementById('final-lat');
        const finalLngInput = document.getElementById('final-lng');
        const attendanceForm = document.getElementById('attendance-main-form');

        let stream;
        let isPhotoTaken = false;
        let isLocationAcquired = false;
        let selectedStatus = null;

        // Initialize Camera
        async function initCamera() {
            if (!window.isSecureContext) {
                cameraLoading.classList.add('hidden');
                cameraError.classList.remove('hidden');
                cameraError.querySelector('p').innerText = "Koneksi Tidak Aman (Bukan HTTPS)";
                cameraError.querySelector('p:last-child').innerText = "Gunakan localhost atau domain dengan SSL.";
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                video.srcObject = stream;
                cameraLoading.classList.add('hidden');
            } catch (err) {
                console.error("Camera error:", err);
                cameraLoading.classList.add('hidden');
                cameraError.classList.remove('hidden');
                captureBtn.disabled = true;
                captureBtn.classList.add('opacity-50');
            }
        }

        // Initialize Location & Reverse Geocode
        function initLocation() {
            if (!navigator.geolocation) {
                locationText.innerText = "Browser tidak mendukung GPS";
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    finalLatInput.value = lat;
                    finalLngInput.value = lng;
                    
                    try {
                        // Reverse Geocoding via Nominatim (Free)
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
                        const data = await response.json();
                        const address = data.display_name.split(',').slice(0, 3).join(',');
                        locationText.innerText = address || `Terdeteksi (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                    } catch (e) {
                        locationText.innerText = `Terdeteksi (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                    }
                    
                    isLocationAcquired = true;
                    locationDot.classList.replace('bg-orange-500', 'bg-green-500');
                    locationPing.classList.replace('bg-orange-400', 'bg-green-400');
                    
                    checkVerification();
                },
                (err) => {
                    console.error("Location error:", err);
                    locationText.innerText = "Izin Lokasi Ditolak / Tidak Tersedia";
                    locationDot.classList.replace('bg-orange-500', 'bg-red-500');
                    locationPing.classList.add('hidden');
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        // Status Selection Logic
        window.selectStatus = function(status) {
            selectedStatus = status;
            finalStatusInput.value = status;
            
            // UI Update
            document.querySelectorAll('.status-choice').forEach(btn => {
                btn.classList.remove('border-primary', 'bg-primary/5', 'shadow-md');
                btn.classList.add('border-slate-100', 'bg-white');
            });
            
            const activeBtn = document.getElementById(`status-${status}`);
            activeBtn.classList.remove('border-slate-100', 'bg-white');
            activeBtn.classList.add('border-primary', 'bg-primary/5', 'shadow-md');
            
            checkVerification();
        };

        function checkVerification() {
            if (isPhotoTaken && isLocationAcquired) {
                actionButtons.classList.remove('opacity-40', 'pointer-events-none', 'blur-[2px]');
                
                if (selectedStatus) {
                    submitBtn.disabled = false;
                    submitBtn.classList.replace('bg-slate-200', 'bg-primary');
                    submitBtn.classList.replace('text-slate-500', 'text-white');
                    submitBtn.classList.add('shadow-primary/30');
                }
            }
        }

        // Capture Action
        captureBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            
            const imageData = canvas.toDataURL('image/jpeg');
            finalImageInput.value = imageData;
            
            photoPreview.src = imageData;
            photoPreview.classList.remove('hidden');
            video.classList.add('hidden');
            
            captureBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            
            isPhotoTaken = true;
            checkVerification();
        });

        // Retake Action
        retakeBtn.addEventListener('click', () => {
            photoPreview.classList.add('hidden');
            video.classList.remove('hidden');
            captureBtn.classList.remove('hidden');
            retakeBtn.classList.add('hidden');
            
            isPhotoTaken = false;
            submitBtn.disabled = true;
            submitBtn.classList.replace('bg-primary', 'bg-slate-200');
            submitBtn.classList.replace('text-white', 'text-slate-500');
        });

        // Form Submit with Confirmation
        attendanceForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (!selectedStatus) {
                alert("Silakan pilih status kehadiran terlebih dahulu.");
                return;
            }

            const msg = `Apakah Anda yakin ingin melakukan presensi dengan status: ${selectedStatus}?`;
            if (confirm(msg)) {
                attendanceForm.submit();
            }
        });

        initCamera();
        initLocation();
    });
</script>
@endpush
@endsection
