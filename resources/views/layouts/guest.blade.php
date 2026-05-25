<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Arsip DKPP Way Kanan') }}</title>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-image-dark min-h-screen font-sans antialiased flex flex-col">
    <div class="flex-1">
        @yield('content')
    </div>

    <div class="px-4 pb-4 pt-2 md:px-5">
        <footer class="rounded-2xl px-4 md:px-6 py-3 flex flex-col sm:flex-row items-center justify-between gap-1"
                style="background: var(--bg-navbar); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border-glass); box-shadow: var(--shadow-card);">
            <p class="text-xs text-theme-muted text-center sm:text-left">
                &copy; {{ date('Y') }} Dinas Ketahanan Pangan Kab. Way Kanan
            </p>
            <p class="text-xs text-theme-muted">E-Arsip v1.0</p>
        </footer>
    </div>
</body>
</html>
