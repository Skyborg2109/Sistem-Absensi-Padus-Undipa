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
                        /* ---- UNDIPA Brand Palette ---- */
                        /* Navy Blue: #003087, Ivory Yellow (Kuning Gading): #F0D060 */
                        "undipa-navy":        "#003087",
                        "undipa-navy-dark":   "#001a4d",
                        "undipa-navy-light":  "#0040a8",
                        "undipa-gold":        "#F0D060",
                        "undipa-gold-light":  "#F7E586",
                        "undipa-gold-dark":   "#C8A820",

                        /* Semantic mappings to brand */
                        "primary":            "#003087",
                        "primary-container":  "#001a4d",
                        "on-primary":         "#ffffff",
                        "on-primary-fixed":   "#ffffff",
                        "on-primary-container": "#F0D060",
                        "primary-fixed":      "#d4e3ff",
                        "primary-fixed-dim":  "#a8c5ff",
                        "on-primary-fixed-variant": "#1a3d78",
                        "inverse-primary":    "#a8c5ff",

                        "secondary":          "#4a5568",
                        "secondary-container": "#dbeafe",
                        "on-secondary":       "#ffffff",
                        "on-secondary-container": "#1e3a5f",
                        "secondary-fixed":    "#dbeafe",
                        "secondary-fixed-dim":"#bfdbfe",
                        "on-secondary-fixed": "#1e3a5f",
                        "on-secondary-fixed-variant": "#2d4a6e",

                        /* Ivory Yellow (Kuning Gading) as tertiary */
                        "tertiary":           "#C8A820",
                        "tertiary-container": "#F0D060",
                        "on-tertiary":        "#001a4d",
                        "on-tertiary-container": "#001a4d",
                        "tertiary-fixed":     "#F7E586",
                        "tertiary-fixed-dim": "#F0D060",
                        "on-tertiary-fixed":  "#001a4d",
                        "on-tertiary-fixed-variant": "#003087",

                        "surface":            "#f4f6fb",
                        "surface-dim":        "#d5d8e0",
                        "surface-bright":     "#f8f9fc",
                        "surface-tint":       "#003087",
                        "surface-variant":    "#dce2f0",
                        "surface-container":  "#eaecf5",
                        "surface-container-low": "#f0f2f8",
                        "surface-container-high": "#e2e5ef",
                        "surface-container-highest": "#d8dcea",
                        "surface-container-lowest": "#ffffff",

                        "on-surface":         "#0f172a",
                        "on-surface-variant": "#44495a",
                        "inverse-surface":    "#1e2433",
                        "inverse-on-surface": "#eef0f6",

                        "outline":            "#6b738a",
                        "outline-variant":    "#c1c8dc",
                        "background":         "#f4f6fb",
                        "on-background":      "#0f172a",

                        "error":              "#ba1a1a",
                        "on-error":           "#ffffff",
                        "error-container":    "#ffdad6",
                        "on-error-container": "#93000a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.375rem",
                        "lg": "0.625rem",
                        "xl": "1rem",
                        "2xl": "1.25rem",
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
    <style>
        /* UNDIPA custom CSS for sidebar gradient & gold accents */
        .undipa-sidebar {
            background: linear-gradient(160deg, #001a4d 0%, #003087 55%, #002060 100%);
        }
        .undipa-sidebar .nav-active {
            background: linear-gradient(90deg, rgba(240,208,96,0.18) 0%, rgba(240,208,96,0.08) 100%);
            border-left: 3px solid #F0D060;
            color: #F7E586 !important;
        }
        .undipa-sidebar .nav-active .material-symbols-outlined {
            color: #F7E586 !important;
        }
        .undipa-sidebar .nav-inactive {
            color: rgba(200,220,255,0.70);
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .undipa-sidebar .nav-inactive:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
            transform: translateX(4px);
            border-left-color: rgba(240,208,96,0.5);
        }
        .undipa-sidebar .nav-inactive .material-symbols-outlined {
            color: rgba(200,220,255,0.60);
            transition: color 0.2s ease;
        }
        .undipa-sidebar .nav-inactive:hover .material-symbols-outlined {
            color: #F7E586;
        }
        .undipa-cta-btn {
            background: linear-gradient(135deg, #F0D060 0%, #F7E586 50%, #C8A820 100%);
            color: #001a4d;
            font-weight: 800;
            box-shadow: 0 4px 20px rgba(240,208,96,0.4);
            transition: all 0.2s ease;
        }
        .undipa-cta-btn:hover {
            box-shadow: 0 6px 28px rgba(240,208,96,0.55);
            transform: scale(1.01);
        }
        .undipa-topbar {
            background: linear-gradient(90deg, #003087 0%, #001a4d 100%);
            border-bottom: 2px solid rgba(240,208,96,0.3);
        }
        .undipa-bottom-nav {
            background: linear-gradient(180deg, rgba(0,48,135,0.97) 0%, rgba(0,26,77,0.97) 100%);
            border-top: 2px solid rgba(240,208,96,0.3);
        }
        .undipa-bottom-nav .nav-active-bottom {
            color: #F7E586 !important;
        }
        .undipa-bottom-nav .nav-inactive-bottom {
            color: rgba(200,220,255,0.55);
        }
        .stat-card-glow {
            box-shadow: 0 4px 24px rgba(0,48,135,0.08), 0 1px 4px rgba(0,0,0,0.04);
        }
        .gold-accent-bar {
            background: linear-gradient(180deg, #F7E586 0%, #F0D060 100%);
        }
        .navy-accent-bar {
            background: linear-gradient(180deg, #003087 0%, #001a4d 100%);
        }
        .chart-bar-active {
            background: linear-gradient(to top, #001a4d, #003087);
        }
        .chart-bar-gold {
            background: linear-gradient(to top, #C8A820, #F7E586);
        }
        /* Mobile topbar nav item glow */
        .undipa-topbar .nav-icon-btn {
            background: rgba(255,255,255,0.12);
            color: #F7E586;
            border-radius: 9999px;
            transition: background 0.2s;
        }
        .undipa-topbar .nav-icon-btn:hover {
            background: rgba(240,208,96,0.25);
        }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f0f2f8; }
        ::-webkit-scrollbar-thumb { background: #aec5e8; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #003087; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-x-hidden min-h-screen flex">
    <!-- SideNavBar -->
    <nav class="hidden md:flex flex-col h-screen py-8 px-3 gap-1 undipa-sidebar w-64 sticky top-0 shadow-2xl">
        <!-- Header -->
        <div class="mb-6 px-3">
            <div class="flex items-center gap-3 mb-1">
                <div class="relative">
                    <img alt="Universitas Dipa Makassar Logo" class="w-12 h-12 rounded-full object-cover border-2 border-undipa-gold shadow-lg shadow-undipa-gold/30" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhd7QGx1YJVOs_x9O456oduan3IQcnpeaOTHrtG-FQNVBgIyVh37lDazvNdYISs8m4C9WfjwV5KXFtsrECLIzRYFVN_D2T_TmROQGIH5P5Sct-7CvvGrS1lXkiqXCeLYVElodfk04hxXc2X6IBBiWUdiU7fwnL2K-Bxkn3GMBENWXyORX5mT2JkR3Ne/s955/LogoUndipa.png"/>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-undipa-gold rounded-full border-2 border-undipa-navy-dark"></div>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-white uppercase tracking-widest font-['Plus_Jakarta_Sans'] leading-tight">PADUS UNDIPA</h1>
                    <p class="text-[10px] text-undipa-gold font-semibold tracking-wider uppercase">Universitas Dipa Makassar</p>
                </div>
            </div>
            <!-- Divider -->
            <div class="mt-4 h-px bg-gradient-to-r from-transparent via-undipa-gold/40 to-transparent"></div>
        </div>
        
        <!-- Navigation Links -->
        <div class="flex flex-col gap-0.5 flex-grow px-1">
            @php $isMember = auth()->check() && auth()->user()->role === 'member'; @endphp

            @if($isMember)
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('member/dashboard') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/member/dashboard') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('member/dashboard') ? '1' : '0' }};">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('member/attendance/history') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/member/attendance/history') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('member/attendance/history') ? '1' : '0' }};">history</span>
                Riwayat Absen
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->routeIs('member.announcements.*') ? 'nav-active' : 'nav-inactive' }}" href="{{ route('member.announcements.index') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('member.announcements.*') ? '1' : '0' }};">campaign</span>
                Papan Informasi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('member/attendance/check-in') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/member/attendance/check-in') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('member/attendance/check-in') ? '1' : '0' }};">how_to_reg</span>
                Check-in Mandiri
            </a>
            @else
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('admin/dashboard') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/admin/dashboard') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/dashboard') ? '1' : '0' }};">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('admin/schedules*') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/admin/schedules') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/schedules*') ? '1' : '0' }};">calendar_month</span>
                Jadwal Latihan
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('admin/attendance*') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/admin/attendance') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/attendance*') ? '1' : '0' }};">how_to_reg</span>
                Absensi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->is('admin/members*') ? 'nav-active' : 'nav-inactive' }}" href="{{ url('/admin/members') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/members*') ? '1' : '0' }};">group</span>
                Anggota
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold {{ request()->routeIs('admin.announcements.*') ? 'nav-active' : 'nav-inactive' }}" href="{{ route('admin.announcements.index') }}">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.announcements.*') ? '1' : '0' }};">campaign</span>
                Informasi
            </a>
            @endif
        </div>
        
        <!-- CTA & Footer -->
        <div class="mt-auto flex flex-col gap-3 px-1">
            <!-- Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-undipa-gold/40 to-transparent"></div>
            @if($isMember)
            <a href="{{ url('/member/attendance/check-in') }}" class="undipa-cta-btn w-full py-3 px-4 rounded-xl font-['Plus_Jakarta_Sans'] text-sm text-center block">
                ✦ Check-in Sesi Sekarang
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 nav-inactive rounded-xl font-['Plus_Jakarta_Sans'] text-sm font-semibold text-red-300 hover:text-red-200 hover:bg-red-900/20 transition-all duration-200">
                    <span class="material-symbols-outlined text-[20px] text-red-300">logout</span>
                    Keluar
                </button>
            </form>
            <!-- Role Badge -->
            <div class="px-3 pb-1">
                <div class="flex items-center gap-2 bg-white/5 rounded-lg px-3 py-2">
                    <div class="w-7 h-7 rounded-full bg-undipa-gold/20 border border-undipa-gold/40 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[14px] text-undipa-gold">{{ auth()->user()->role === 'admin' ? 'shield' : 'person' }}</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-white leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-undipa-gold/80 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Canvas -->
    <main class="flex-1 flex flex-col min-h-screen bg-surface relative z-10 overflow-hidden">
        <!-- TopAppBar (Mobile Only) -->
        <header class="md:hidden flex justify-between items-center px-5 py-3 w-full undipa-topbar fixed top-0 z-50 shadow-lg">
            <div class="flex items-center gap-2.5">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhd7QGx1YJVOs_x9O456oduan3IQcnpeaOTHrtG-FQNVBgIyVh37lDazvNdYISs8m4C9WfjwV5KXFtsrECLIzRYFVN_D2T_TmROQGIH5P5Sct-7CvvGrS1lXkiqXCeLYVElodfk04hxXc2X6IBBiWUdiU7fwnL2K-Bxkn3GMBENWXyORX5mT2JkR3Ne/s955/LogoUndipa.png" class="w-7 h-7 rounded-full object-cover border border-undipa-gold/60" alt="Logo UNDIPA"/>
                <span class="text-base font-extrabold text-white font-['Plus_Jakarta_Sans'] tracking-tight">Dipa <span class="text-undipa-gold">Choir</span></span>
            </div>
            <div class="flex items-center gap-2 relative">
                <button onclick="document.getElementById('notifDropdownMobile').classList.toggle('hidden')" class="relative nav-icon-btn p-2 undipa-topbar">
                    <span class="material-symbols-outlined text-[22px] text-undipa-gold-light">notifications</span>
                    <span id="mobileNotifCount" class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-400 rounded-full hidden border border-white"></span>
                </button>
                <!-- Mobile Notification Dropdown -->
                <div id="notifDropdownMobile" class="hidden absolute top-14 right-0 w-[calc(100vw-3rem)] max-w-sm bg-white shadow-[0_10px_40px_rgba(0,48,135,0.15)] rounded-2xl border border-blue-100 z-50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-blue-100 flex justify-between items-center" style="background: linear-gradient(90deg,#003087,#001a4d);">
                        <h3 class="font-bold text-white text-sm">Notifikasi</h3>
                        <button onclick="document.getElementById('notifDropdownMobile').classList.add('hidden')" class="text-undipa-gold hover:text-undipa-gold-light">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                    <div id="notifListMobile" class="max-h-64 overflow-y-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">Tidak ada notifikasi</div>
                    </div>
                </div>
                <a href="{{ url('/settings') }}" class="nav-icon-btn p-2 undipa-topbar">
                    <span class="material-symbols-outlined text-[22px] text-blue-200">settings</span>
                </a>
                <a href="{{ url('/profile') }}">
                    <img alt="User profile photo" class="w-8 h-8 rounded-full object-cover border-2 border-undipa-gold shadow" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMS-Jw1uWJ4xxtA-LPZI9F2ZiRIt6PxceB1tsOBUzOs6V_ul5XGvq-cdLKPfuIWOT6uDGqYqOl6rp0YIof7WRWFx6HYgjtNAvFkj_AYU7LrYXCkGzx13NMi7ufRlWGXsvmCDzo8IXY6P7kN8AwNCZDHi3GSlSqvy20kLZekGjxbj2LiZoG7h10gJ1nV75gUVvGaVMfaLhL6nN0bqxx8XNRDPuviP-xffFsZS3r2TqTEm12O3XofACQ2JiHLttP0QJrvCRpS9SPupE"/>
                </a>
            </div>
        </header>

        <!-- TopAppBar (Desktop) -->
        <header class="hidden md:flex justify-between items-center px-10 w-full bg-white border-b-2 border-undipa-gold/20 shadow-md h-20 z-40 sticky top-0">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 gold-accent-bar rounded-full"></div>
                <h2 class="text-xl font-extrabold text-undipa-navy font-['Plus_Jakarta_Sans'] tracking-tight">@yield('title', 'Sistem Absensi')</h2>
            </div>
            <div class="flex items-center gap-3 relative">
                <button onclick="document.getElementById('notifDropdownDesktop').classList.toggle('hidden')" class="relative hover:bg-blue-50 transition-colors p-2.5 rounded-full duration-200 bg-blue-50/50 border border-blue-100 text-undipa-navy">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="desktopNotifCount" class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full hidden border-2 border-white"></span>
                </button>
                <!-- Desktop Notification Dropdown -->
                <div id="notifDropdownDesktop" class="hidden absolute top-14 right-20 w-80 bg-white shadow-[0_4px_32px_rgba(0,48,135,0.15)] rounded-2xl border border-blue-100 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-blue-100 flex justify-between items-center" style="background: linear-gradient(90deg,#003087,#001a4d);">
                        <h3 class="font-bold text-white text-sm">Notifikasi</h3>
                    </div>
                    <div id="notifListDesktop" class="max-h-64 overflow-y-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">Tidak ada notifikasi</div>
                    </div>
                </div>
                <a href="{{ url('/settings') }}" class="hover:bg-blue-50 transition-colors p-2.5 rounded-full duration-200 border border-blue-100 text-undipa-navy">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.announcements.index') : route('member.announcements.index') }}" class="hover:bg-blue-50 transition-colors p-2.5 rounded-full duration-200 border border-blue-100 text-undipa-navy flex items-center justify-center" title="Peraturan & Informasi">
                    <span class="material-symbols-outlined">description</span>
                </a>
                <a href="{{ url('/profile') }}" class="hover:ring-4 ring-undipa-gold/30 transition-all rounded-full ml-1 cursor-pointer">
                    <img alt="User profile photo" class="w-11 h-11 rounded-full object-cover border-2 border-undipa-gold shadow-md" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMS-Jw1uWJ4xxtA-LPZI9F2ZiRIt6PxceB1tsOBUzOs6V_ul5XGvq-cdLKPfuIWOT6uDGqYqOl6rp0YIof7WRWFx6HYgjtNAvFkj_AYU7LrYXCkGzx13NMi7ufRlWGXsvmCDzo8IXY6P7kN8AwNCZDHi3GSlSqvy20kLZekGjxbj2LiZoG7h10gJ1nV75gUVvGaVMfaLhL6nN0bqxx8XNRDPuviP-xffFsZS3r2TqTEm12O3XofACQ2JiHLttP0QJrvCRpS9SPupE"/>
                </a>
            </div>
        </header>

        <!-- Dynamic Page Content -->
        <div class="flex-1 overflow-y-auto px-6 lg:px-12 pt-24 md:pt-10 pb-24 relative z-0 bg-surface">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navbar (Mobile Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full undipa-bottom-nav z-50 flex justify-around items-center px-2 py-2 shadow-[0_-4px_32px_rgba(0,48,135,0.3)]">
        @php $isMember = auth()->check() && auth()->user()->role === 'member'; @endphp
        
        @if($isMember)
            <a href="{{ url('/member/dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('member/dashboard') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('member/dashboard') ? '1' : '0' }};">dashboard</span>
                <span class="text-[9px] font-bold tracking-wide">Beranda</span>
            </a>
            <a href="{{ url('/member/attendance/history') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('member/attendance/history') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('member/attendance/history') ? '1' : '0' }};">history</span>
                <span class="text-[9px] font-bold tracking-wide">Riwayat</span>
            </a>
            <!-- Center Check-in FAB -->
            <a href="{{ url('/member/attendance/check-in') }}" class="flex flex-col items-center gap-0.5 -mt-5 px-3">
                <div class="undipa-cta-btn w-14 h-14 rounded-full flex items-center justify-center shadow-xl shadow-undipa-gold/40">
                    <span class="material-symbols-outlined text-[26px]" style="font-variation-settings: 'FILL' 1;">how_to_reg</span>
                </div>
                <span class="text-[9px] font-bold text-undipa-gold tracking-wide mt-0.5">Absen</span>
            </a>
            <a href="{{ route('member.announcements.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('member.announcements.*') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('member.announcements.*') ? '1' : '0' }};">campaign</span>
                <span class="text-[9px] font-bold tracking-wide">Info</span>
            </a>
            <a href="{{ url('/settings') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('settings') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('settings') ? '1' : '0' }};">settings</span>
                <span class="text-[9px] font-bold tracking-wide">Setelan</span>
            </a>
        @else
            <a href="{{ url('/admin/dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('admin/dashboard') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/dashboard') ? '1' : '0' }};">dashboard</span>
                <span class="text-[9px] font-bold tracking-wide">Beranda</span>
            </a>
            <a href="{{ url('/admin/schedules') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('admin/schedules*') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/schedules*') ? '1' : '0' }};">calendar_month</span>
                <span class="text-[9px] font-bold tracking-wide">Jadwal</span>
            </a>
            <a href="{{ url('/admin/attendance') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('admin/attendance*') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('admin/attendance*') ? '1' : '0' }};">how_to_reg</span>
                <span class="text-[9px] font-bold tracking-wide">Absensi</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('admin.announcements.*') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.announcements.*') ? '1' : '0' }};">campaign</span>
                <span class="text-[9px] font-bold tracking-wide">Info</span>
            </a>
            <a href="{{ url('/settings') }}" class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('settings') ? 'nav-active-bottom' : 'nav-inactive-bottom' }}">
                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' {{ request()->is('settings') ? '1' : '0' }};">settings</span>
                <span class="text-[9px] font-bold tracking-wide">Setelan</span>
            </a>
        @endif
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
