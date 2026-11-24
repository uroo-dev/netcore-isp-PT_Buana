<header
    class="bg-white dark:bg-dark-800 shadow-sm sticky top-0 z-10 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between border-b border-gray-200 dark:border-dark-700 transition-colors duration-200">
    <div class="flex items-center gap-3">
        <!-- Mobile Menu Button - Memanggil fungsi global toggleMobileSidebar -->
        <button id="mobileMenuToggle" onclick="window.toggleMobileSidebar()"
            class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 lg:hidden transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <!-- Page Title -->
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white transition-colors">@yield('title', 'BuanaNet')</h2>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Theme Toggle Button -->
        {{-- <button id="headerThemeToggle" onclick="window.toggleTheme()"
            class="relative p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-700 hover:text-gray-600 dark:hover:text-yellow-400 transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-gray-500"
            aria-label="Toggle Theme">
            <i id="headerThemeIcon" class='bx bx-moon text-xl group-hover:scale-110 transition-transform'></i>
            <span
                class="absolute -top-1 -right-1 w-2 h-2 bg-gray-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
        </button> --}}

        <!-- User Info -->
        <div class="flex items-center gap-2 sm:gap-3 pl-2 sm:pl-3 border-l border-gray-200 dark:border-dark-700">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ auth()->user()->role ?? 'User' }}</p>
            </div>

            <!-- Avatar with dropdown indicator -->
            <div class="relative group">
                <img class="w-9 h-9 rounded-full ring-2 ring-gray-200 dark:ring-blue-900 object-cover cursor-pointer transition-transform group-hover:scale-105"
                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_lengkap) }}&background=1e3a8a&color=fbbf24&bold=true&length=2"
                    alt="{{ auth()->user()->nama_lengkap }}">

                <!-- Status Online Indicator -->
                <span
                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-dark-800 rounded-full"></span>
            </div>
        </div>
    </div>
</header>

<script>
    // ========== HEADER THEME SYNC ==========
    (function() {
        // Fungsi untuk update tampilan tombol di header berdasarkan tema
        function updateHeaderThemeUI() {
            const isDark = document.documentElement.classList.contains('dark-mode');
            const icon = document.getElementById('headerThemeIcon');

            if (icon) {
                if (isDark) {
                    icon.classList.remove('bx-moon');
                    icon.classList.add('bx-sun');
                } else {
                    icon.classList.remove('bx-sun');
                    icon.classList.add('bx-moon');
                }
            }
        }

        // Panggil pertama kali
        updateHeaderThemeUI();

        // Dengarkan event perubahan tema dari sistem global
        if (typeof window !== 'undefined') {
            window.addEventListener('themeChanged', function(e) {
                updateHeaderThemeUI();
            });
        }
    })();

    // ========== HEADER SCROLL EFFECT ==========
    (function() {
        const header = document.querySelector('header');
        if (header) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }
    })();

    // ========== PASTIKAN FUNGSI GLOBAL TERSEDIA ==========
    // Fungsi toggleTheme (jika belum ada dari sidebar)
    if (typeof window.toggleTheme !== 'function') {
        window.toggleTheme = function() {
            const isDark = document.documentElement.classList.contains('dark-mode');
            const newTheme = isDark ? 'light' : 'dark';

            if (newTheme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light-mode');
                if (document.body) {
                    document.body.classList.add('dark-mode');
                    document.body.classList.remove('light-mode');
                }
            } else {
                document.documentElement.classList.remove('dark-mode');
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light-mode');
                if (document.body) {
                    document.body.classList.remove('dark-mode');
                    document.body.classList.add('light-mode');
                }
            }

            localStorage.setItem('buana_theme_preference', newTheme);
            window.dispatchEvent(new CustomEvent('themeChanged', {
                detail: {
                    theme: newTheme
                }
            }));
        };
    }

    // Fungsi toggleMobileSidebar (jika belum ada dari sidebar)
    if (typeof window.toggleMobileSidebar !== 'function') {
        window.toggleMobileSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Buka sidebar
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    if (overlay) {
                        overlay.classList.remove('hidden');
                    }
                    document.body.style.overflow = 'hidden';
                } else {
                    // Tutup sidebar
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    if (overlay) {
                        overlay.classList.add('hidden');
                    }
                    document.body.style.overflow = '';
                }
            }
        };
    }
</script>

<style>
    /* Transisi smooth untuk header */
    header {
        backdrop-filter: blur(0px);
        transition: all 0.2s ease;
    }

    /* Header shadow on scroll */
    header.scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Dark mode header scrolled */
    .dark-mode header.scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    /* Avatar hover effect */
    header .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }

    /* Focus ring untuk aksesibilitas */
    header button:focus-visible {
        outline: none;
        ring: 2px solid #3b82f6;
    }

    /* Animasi pulse untuk status online */
    @keyframes pulse-green {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.6;
            transform: scale(1.1);
        }
    }

    header .absolute.bottom-0.right-0 {
        animation: pulse-green 2s infinite;
    }
</style>
