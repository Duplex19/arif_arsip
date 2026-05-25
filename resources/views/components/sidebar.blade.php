@php
    $user = auth()->user();
    $menuUtama = [];
    $menuAdmin = [];
    $menuLainnya = [];

    $menuUtama[] = ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'];
    $menuUtama[] = ['label' => 'Arsip', 'route' => 'arsip.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];

    if ($user->isAdmin() || $user->isUser()) {
        $menuUtama[] = ['label' => 'Unggah Arsip', 'route' => 'arsip.create', 'icon' => 'M12 4v16m8-8H4'];
    }

    if ($user->isAdmin()) {
        $menuAdmin[] = ['label' => 'Kelola User', 'route' => 'admin.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'];
        $menuAdmin[] = ['label' => 'Kelola Bidang', 'route' => 'admin.bidang.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'];
        $menuAdmin[] = ['label' => 'Jenis Arsip', 'route' => 'admin.jenis-arsip.index', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'];
    }

    $menuLainnya[] = ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'];
@endphp

{{-- Mobile: fixed overlay | Desktop: sticky in flex flow --}}
<aside id="sidebar"
     class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300
            lg:translate-x-0 lg:sticky lg:top-[96px] lg:inset-auto lg:z-0 lg:max-h-[calc(100vh-128px)]
            rounded-2xl flex flex-col glass-sidebar">
    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-5">
        <div>
            <p class="text-[10px] font-semibold text-theme-muted uppercase tracking-widest px-3 mb-2">Menu Utama</p>
            <div class="space-y-0.5">
                @foreach($menuUtama as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="sidebar-link {{ $isActive ? 'active' : '' }}">
                        <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        @if(count($menuAdmin) > 0)
            <div>
                <p class="text-[10px] font-semibold text-theme-muted uppercase tracking-widest px-3 mb-2">Administrasi</p>
                <div class="space-y-0.5">
                    @foreach($menuAdmin as $item)
                        @php $isActive = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="sidebar-link {{ $isActive ? 'active' : '' }}">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <p class="text-[10px] font-semibold text-theme-muted uppercase tracking-widest px-3 mb-2">Lainnya</p>
            <div class="space-y-0.5">
                @foreach($menuLainnya as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="sidebar-link {{ $isActive ? 'active' : '' }}">
                        <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Logout --}}
    <div class="px-4 py-4 border-t border-white/10 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-theme-muted hover:bg-red-500/10 hover:text-red-400 w-full transition-all duration-200 group">
                <div class="w-[18px] h-[18px] flex items-center justify-center flex-shrink-0">
                    <svg class="w-[18px] h-[18px] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <span class="font-medium">Keluar</span>
            </button>
        </form>
    </div>
</aside>
