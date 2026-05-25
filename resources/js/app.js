import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

// ============================================
// THEME SWITCHER
// ============================================

const ThemeManager = {
    init() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        this.setTheme(savedTheme);
        this.bindToggle();
    },

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        this.updateToggleIcon(theme);
        this.updateBodyClass(theme);
    },

    toggle() {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        this.setTheme(next);
    },

    updateToggleIcon(theme) {
        const toggleBtn = document.getElementById('themeToggle');
        if (!toggleBtn) return;

        if (theme === 'dark') {
            toggleBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>`;
            toggleBtn.title = 'Switch to Light Mode';
        } else {
            toggleBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>`;
            toggleBtn.title = 'Switch to Dark Mode';
        }
    },

    updateBodyClass(theme) {
        const body = document.body;
        body.classList.remove('bg-image-dark', 'bg-image-light');
        body.classList.add(theme === 'dark' ? 'bg-image-dark' : 'bg-image-light');
    },

    bindToggle() {
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggle());
        }
    }
};

// ============================================
// SIDEBAR MOBILE
// ============================================

const SidebarManager = {
    init() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('-translate-x-full');
            });

            document.addEventListener('click', (e) => {
                if (window.innerWidth < 1024 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }
    }
};

// ============================================
// ALERT AUTO-DISMISS
// ============================================

const AlertManager = {
    init() {
        document.querySelectorAll('[data-auto-dismiss]').forEach(alert => {
            const delay = parseInt(alert.dataset.autoDismiss) || 5000;
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, delay);
        });
    }
};

// ============================================
// ANIMATED COUNTERS
// ============================================

const CounterManager = {
    init() {
        document.querySelectorAll('[data-counter]').forEach(el => {
            const target = parseInt(el.dataset.counter);
            const duration = 1000;
            const start = performance.now();

            const animate = (now) => {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(easeOut * target).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };

            requestAnimationFrame(animate);
        });
    }
};

// ============================================
// INITIALIZE
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    SidebarManager.init();
    AlertManager.init();
    CounterManager.init();
});
