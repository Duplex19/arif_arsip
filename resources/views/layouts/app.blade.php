<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - E-Arsip DKP Way Kanan</title>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-image-dark min-h-screen font-sans antialiased flex flex-col gap-4 p-4 md:p-5">
    <x-navbar />

    <div class="flex-1 flex gap-4">
        <x-sidebar />

        <main class="flex-1 rounded-2xl p-4 md:p-6 flex flex-col" style="background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border-card); box-shadow: var(--shadow-card);">
            <div class="flex-1">
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" data-auto-dismiss="5000" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" data-auto-dismiss="8000" />
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <x-footer />

    @stack('scripts')
</body>
</html>
