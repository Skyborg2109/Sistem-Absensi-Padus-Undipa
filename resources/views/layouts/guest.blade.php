<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Sistem Absensi Paduan Suara')</title>
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4 antialiased text-on-surface relative overflow-hidden" style="background-image: url('{{ asset('Foto/gambar padus.jpeg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Dark overlay for better text readability -->
    <div class="absolute inset-0 z-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
    
    <main class="w-full max-w-md relative z-10">
        @yield('content')
    </main>
</body>
</html>
