<footer class="w-full flex-shrink-0 rounded-2xl px-4 md:px-6 py-3 flex flex-col sm:flex-row items-center justify-between gap-1"
        style="background: var(--bg-navbar); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border-glass); box-shadow: var(--shadow-card);">
    <p class="text-xs text-theme-muted text-center sm:text-left">
        &copy; {{ date('Y') }}
        <span class="font-medium text-theme-secondary">Dinas Ketahanan Pangan</span>
        Kabupaten Way Kanan
    </p>
    <div class="flex items-center gap-3 text-xs text-theme-muted">
        <span>E-Arsip v1.0</span>
        <span class="w-1 h-1 rounded-full" style="background: var(--text-muted)"></span>
        <span>Laravel {{ app()->version() }}</span>
    </div>
</footer>
