@php
    $user = auth()->user();
    $roleLabels = ['admin' => 'Admin', 'user' => 'Staf', 'pimpinan' => 'Pimpinan'];
    $roleColors = [
        'admin' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30',
        'user' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
        'pimpinan' => 'bg-amber-500/20 text-amber-400 border-amber-500/30'
    ];
@endphp

<nav class="glass-navbar w-full flex-shrink-0 px-4 py-3 z-30 rounded-2xl">
    <div class="flex items-center justify-between max-w-full">
        <div class="flex items-center gap-3">
            <button id="sidebarToggle" class="lg:hidden glass-button p-2 text-theme-muted hover:text-theme-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Brand di desktop --}}
            <div class="hidden lg:flex items-center gap-3">
                <img src="{{ asset('images/logo-way-kanan.png') }}" alt="Lambang Way Kanan"
                     class="w-9 h-9 rounded-lg object-cover shadow-md shadow-indigo-500/20">
                <div>
                    <h2 class="text-sm font-bold text-theme-primary">E-Arsip DKP</h2>
                    <p class="text-[10px] text-theme-muted -mt-0.5">Dinas Ketahanan Pangan Way Kanan</p>
                </div>
            </div>

            <div class="lg:hidden">
                <h1 class="text-base font-semibold text-theme-primary truncate max-w-[160px]">@yield('title', 'Dashboard')</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- Theme Toggle --}}
            <button id="themeToggle" class="theme-toggle" title="Switch Theme">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>

            <div class="flex items-center gap-3 glass px-3 py-1.5 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-theme-primary">{{ $user->username }}</p>
                    <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $roleColors[$user->role] ?? 'text-theme-muted border-white/10' }}">
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</nav>
