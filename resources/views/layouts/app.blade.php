<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard - Universitas Dipa Makassar Choir')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary": "#ffffff",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f0f1f2",
                        "on-secondary-fixed-variant": "#3e4758",
                        "secondary": "#565f70",
                        "surface-variant": "#e1e3e4",
                        "inverse-surface": "#2e3132",
                        "inverse-primary": "#aec7f6",
                        "secondary-fixed-dim": "#bec7db",
                        "surface-bright": "#f8f9fa",
                        "on-tertiary-fixed-variant": "#574500",
                        "outline-variant": "#c4c6cf",
                        "on-tertiary-fixed": "#241a00",
                        "surface-container-high": "#e7e8e9",
                        "on-tertiary": "#ffffff",
                        "surface-container-low": "#f3f4f5",
                        "surface-dim": "#d9dadb",
                        "tertiary-fixed-dim": "#e9c349",
                        "primary-container": "#002147",
                        "on-error": "#ffffff",
                        "on-background": "#191c1d",
                        "on-tertiary-container": "#4f3e00",
                        "surface": "#f8f9fa",
                        "on-error-container": "#93000a",
                        "tertiary-container": "#cca830",
                        "surface-container": "#edeeef",
                        "on-primary-fixed-variant": "#2d476f",
                        "outline": "#74777f",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#001b3d",
                        "surface-tint": "#465f88",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#191c1d",
                        "error": "#ba1a1a",
                        "tertiary": "#735c00",
                        "surface-container-highest": "#e1e3e4",
                        "secondary-fixed": "#dae3f8",
                        "background": "#f8f9fa",
                        "on-primary-container": "#708ab5",
                        "on-surface-variant": "#44474e",
                        "primary": "#000a1e",
                        "tertiary-fixed": "#ffe088",
                        "secondary-container": "#d7e0f5",
                        "primary-fixed": "#d6e3ff",
                        "on-secondary-fixed": "#131c2b",
                        "on-secondary-container": "#5a6375",
                        "primary-fixed-dim": "#aec7f6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-x-hidden min-h-screen flex">
    <!-- SideNavBar -->
    <nav class="hidden md:flex flex-col h-screen py-8 px-4 gap-2 bg-slate-50 dark:bg-slate-900 w-64 border-r-0 sticky top-0 flat-no-shadows">
        <!-- Header -->
        <div class="mb-8 px-4">
            <div class="flex items-center gap-3 mb-2">
                <img alt="Universitas Dipa Makassar Logo" class="w-10 h-10 rounded-full object-cover border-2 border-surface" data-alt="Abstract gold and navy intersecting lines symbolizing a choir and ledger" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhd7QGx1YJVOs_x9O456oduan3IQcnpeaOTHrtG-FQNVBgIyVh37lDazvNdYISs8m4C9WfjwV5KXFtsrECLIzRYFVN_D2T_TmROQGIH5P5Sct-7CvvGrS1lXkiqXCeLYVElodfk04hxXc2X6IBBiWUdiU7fwnL2K-Bxkn3GMBENWXyORX5mT2JkR3Ne/s955/LogoUndipa.png"/>
                <div>
                    <h1 class="text-lg font-black text-blue-950 dark:text-white uppercase tracking-widest font-['Plus_Jakarta_Sans']">PADUS UNDIPA</h1>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-medium font-['Plus_Jakarta_Sans']">Sistem Absensi Padus</p>
        </div>
        
        <!-- Navigation Links -->
        <div class="flex flex-col gap-2 flex-grow">
            <!-- Check if current path matches to set active class logic in Blade if needed -->
            @php $isMember = auth()->check() && auth()->user()->role === 'member'; @endphp

            @if($isMember)
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('member/dashboard') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/member/dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('member/attendance/history') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/member/attendance/history') }}">
                <span class="material-symbols-outlined">history</span>
                Riwayat Absen
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('member.announcements.*') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ route('member.announcements.index') }}">
                <span class="material-symbols-outlined">campaign</span>
                Papan Informasi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('member/attendance/check-in') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/member/attendance/check-in') }}">
                <span class="material-symbols-outlined">how_to_reg</span>
                Check-in Mandiri
            </a>
            @else
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('admin/dashboard') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/admin/dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('admin/schedules*') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/admin/schedules') }}">
                <span class="material-symbols-outlined">calendar_month</span>
                Jadwal Latihan
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('admin/attendance*') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/admin/attendance') }}">
                <span class="material-symbols-outlined">how_to_reg</span>
                Absensi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->is('admin/members*') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ url('/admin/members') }}">
                <span class="material-symbols-outlined">group</span>
                Anggota
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.announcements.*') ? 'bg-white dark:bg-blue-950 text-blue-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out' }} rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-medium" href="{{ route('admin.announcements.index') }}">
                <span class="material-symbols-outlined">campaign</span>
                Informasi
            </a>
            @endif
        </div>
        
        <!-- CTA & Footer -->
        <div class="mt-auto flex flex-col gap-4">
            @if($isMember)
            <a href="{{ url('/member/attendance/check-in') }}" class="w-full py-3 px-4 rounded-xl bg-gradient-to-br from-primary to-primary-container text-on-primary font-['Plus_Jakarta_Sans'] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-transform text-center block">
                Check-in Sesi
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-slate-600 dark:text-slate-400 hover:translate-x-1 duration-300 transition-all ease-in-out font-['Plus_Jakarta_Sans'] text-sm font-medium">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content Canvas -->
    <main class="flex-1 flex flex-col min-h-screen bg-surface-container-low md:rounded-l-[2rem] relative z-10 overflow-hidden">
        <!-- TopAppBar (Mobile Only) -->
        <header class="md:hidden flex justify-between items-center px-6 py-3 w-full bg-white/70 dark:bg-slate-950/70 backdrop-blur-xl shadow-sm dark:shadow-none fixed top-0 z-50 tonal-shift-no-borders">
            <span class="text-xl font-bold tracking-tighter text-blue-900 dark:text-white font-['Plus_Jakarta_Sans'] tracking-tight">Dipa Choir</span>
            <div class="flex items-center gap-4 text-blue-950 dark:text-blue-100 relative">
                <button onclick="document.getElementById('notifDropdownMobile').classList.toggle('hidden')" class="relative hover:bg-slate-100/50 dark:hover:bg-slate-800/50 transition-colors p-2 rounded-full scale-105 duration-200">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="mobileNotifCount" class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full hidden border border-white"></span>
                </button>
                <!-- Mobile Notification Dropdown -->
                <div id="notifDropdownMobile" class="hidden absolute top-14 right-0 w-[calc(100vw-3rem)] max-w-sm bg-white shadow-[0_10px_40px_rgba(0,0,0,0.15)] rounded-2xl border border-slate-100 z-50 overflow-hidden translate-y-2">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 text-sm">Notifikasi</h3>
                        <button onclick="document.getElementById('notifDropdownMobile').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                    <div id="notifListMobile" class="max-h-64 overflow-y-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">Tidak ada notifikasi</div>
                    </div>
                </div>
                <a href="{{ url('/settings') }}" class="hover:bg-slate-100/50 dark:hover:bg-slate-800/50 transition-colors p-2 rounded-full scale-95 duration-200 cursor-pointer text-slate-800 dark:text-blue-100">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.announcements.index') : route('member.announcements.index') }}" class="hover:bg-slate-100/50 dark:hover:bg-slate-800/50 transition-colors p-2 rounded-full scale-105 duration-200 cursor-pointer text-slate-800 dark:text-blue-100">
                    <span class="material-symbols-outlined">description</span>
                </a>
                <a href="{{ url('/profile') }}">
                    <img alt="User profile photo" class="w-8 h-8 rounded-full object-cover" data-alt="Professional portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMS-Jw1uWJ4xxtA-LPZI9F2ZiRIt6PxceB1tsOBUzOs6V_ul5XGvq-cdLKPfuIWOT6uDGqYqOl6rp0YIof7WRWFx6HYgjtNAvFkj_AYU7LrYXCkGzx13NMi7ufRlWGXsvmCDzo8IXY6P7kN8AwNCZDHi3GSlSqvy20kLZekGjxbj2LiZoG7h10gJ1nV75gUVvGaVMfaLhL6nN0bqxx8XNRDPuviP-xffFsZS3r2TqTEm12O3XofACQ2JiHLttP0QJrvCRpS9SPupE"/>
                </a>
            </div>
        </header>

        <!-- TopAppBar (Desktop) -->
        <header class="hidden md:flex justify-between items-center px-10 w-full bg-white/70 backdrop-blur-xl border-b border-slate-200/60 shadow-sm h-20 z-40 sticky top-0">
            <div>
                <h2 class="text-xl font-black text-slate-800 font-['Plus_Jakarta_Sans'] tracking-tight">@yield('title', 'Sistem Absensi')</h2>
            </div>
            <div class="flex items-center gap-4 text-slate-700 relative">
                <button onclick="document.getElementById('notifDropdownDesktop').classList.toggle('hidden')" class="relative hover:bg-slate-100 transition-colors p-2.5 rounded-full duration-200 bg-white border border-slate-200/60 shadow-sm text-slate-600">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="desktopNotifCount" class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full hidden border border-white"></span>
                </button>
                <!-- Desktop Notification Dropdown -->
                <div id="notifDropdownDesktop" class="hidden absolute top-12 right-16 w-80 bg-white shadow-[0_4px_24px_rgba(0,0,0,0.1)] rounded-xl border border-slate-100 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 text-sm">Notifikasi</h3>
                    </div>
                    <div id="notifListDesktop" class="max-h-64 overflow-y-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">Tidak ada notifikasi</div>
                    </div>
                </div>
                <a href="{{ url('/settings') }}" class="hover:bg-slate-100 transition-colors p-2.5 rounded-full duration-200 bg-white border border-slate-200/60 shadow-sm text-slate-600">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.announcements.index') : route('member.announcements.index') }}" class="hover:bg-slate-100 transition-colors p-2.5 rounded-full duration-200 bg-white border border-slate-200/60 shadow-sm text-slate-600 flex items-center justify-center" title="Peraturan & Informasi">
                    <span class="material-symbols-outlined">description</span>
                </a>
                <a href="{{ url('/profile') }}" class="hover:ring-4 ring-blue-50 transition-all rounded-full ml-1 cursor-pointer">
                    <img alt="User profile photo" class="w-11 h-11 rounded-full object-cover border border-slate-200 shadow-sm" data-alt="Professional portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMS-Jw1uWJ4xxtA-LPZI9F2ZiRIt6PxceB1tsOBUzOs6V_ul5XGvq-cdLKPfuIWOT6uDGqYqOl6rp0YIof7WRWFx6HYgjtNAvFkj_AYU7LrYXCkGzx13NMi7ufRlWGXsvmCDzo8IXY6P7kN8AwNCZDHi3GSlSqvy20kLZekGjxbj2LiZoG7h10gJ1nV75gUVvGaVMfaLhL6nN0bqxx8XNRDPuviP-xffFsZS3r2TqTEm12O3XofACQ2JiHLttP0QJrvCRpS9SPupE"/>
                </a>
            </div>
        </header>

        <!-- Dynamic Page Content -->
        <div class="flex-1 overflow-y-auto px-6 lg:px-12 pt-24 md:pt-10 pb-24 relative z-0">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navbar (Mobile Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-slate-100 z-50 flex justify-around items-center px-2 py-2 shadow-[0_-4px_24px_rgba(0,0,0,0.02)] pb-safe">
        @php $isMember = auth()->check() && auth()->user()->role === 'member'; @endphp
        
        @if($isMember)
            <a href="{{ url('/member/dashboard') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('member/dashboard') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('member/dashboard') ? '1' : '0' }};">dashboard</span>
                <span class="text-[10px] font-semibold">Beranda</span>
            </a>
            <a href="{{ url('/member/attendance/history') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('member/attendance/history') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('member/attendance/history') ? '1' : '0' }};">history</span>
                <span class="text-[10px] font-semibold">Riwayat</span>
            </a>
            <a href="{{ route('member.announcements.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('member.announcements.*') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('member.announcements.*') ? '1' : '0' }};">campaign</span>
                <span class="text-[10px] font-semibold">Info</span>
            </a>
            <a href="{{ url('/member/attendance/check-in') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('member/attendance/check-in') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('member/attendance/check-in') ? '1' : '0' }};">how_to_reg</span>
                <span class="text-[10px] font-semibold">Absen</span>
            </a>
        @else
            <a href="{{ url('/admin/dashboard') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('admin/dashboard') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/dashboard') ? '1' : '0' }};">dashboard</span>
                <span class="text-[10px] font-semibold">Beranda</span>
            </a>
            <a href="{{ url('/admin/schedules') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('admin/schedules*') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/schedules*') ? '1' : '0' }};">calendar_month</span>
                <span class="text-[10px] font-semibold">Jadwal</span>
            </a>
            <a href="{{ url('/admin/attendance') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('admin/attendance*') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/attendance*') ? '1' : '0' }};">how_to_reg</span>
                <span class="text-[10px] font-semibold">Absensi</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('admin.announcements.*') ? 'text-primary' : 'text-slate-400' }}">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.announcements.*') ? '1' : '0' }};">campaign</span>
                <span class="text-[10px] font-semibold">Info</span>
            </a>
        @endif
        
        <a href="{{ url('/settings') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('settings') ? 'text-primary' : 'text-slate-400' }}">
            <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' {{ request()->is('settings') ? '1' : '0' }};">settings</span>
            <span class="text-[10px] font-semibold">Setelan</span>
        </a>
    </nav>
    
    <!-- Polling Script for Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchNotifications() {
                fetch('{{ route("notifications.unread") }}')
                    .then(response => response.json())
                    .then(data => {
                        const count = data.count;
                        const notifications = data.notifications;
                        
                        const mobileIndicator = document.getElementById('mobileNotifCount');
                        const desktopIndicator = document.getElementById('desktopNotifCount');
                        
                        if (count > 0) {
                            if(mobileIndicator) mobileIndicator.classList.remove('hidden');
                            if(desktopIndicator) desktopIndicator.classList.remove('hidden');
                        } else {
                            if(mobileIndicator) mobileIndicator.classList.add('hidden');
                            if(desktopIndicator) desktopIndicator.classList.add('hidden');
                        }
                        
                        let html = '';
                        if (notifications && notifications.length > 0) {
                            notifications.forEach(notif => {
                                html += `<div class="p-3 border-b border-slate-100 hover:bg-slate-50 text-sm">
                                    <p class="text-slate-800">${notif.data.message || 'Pemberitahuan Sistem'}</p>
                                    <span class="text-xs text-slate-400 mt-1 block">${new Date(notif.created_at).toLocaleDateString()}</span>
                                </div>`;
                            });
                        } else {
                            html = '<div class="p-4 text-center text-slate-500 text-sm">Tidak ada notifikasi baru</div>';
                        }
                        
                        const mList = document.getElementById('notifListMobile');
                        const dList = document.getElementById('notifListDesktop');
                        if(mList) mList.innerHTML = html;
                        if(dList) dList.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }
            
            // Check immediately on load
            fetchNotifications();
            
            // Then poll every 15 seconds
            setInterval(fetchNotifications, 15000);
        });
    </script>
</body>
</html>
