@php
    if (!function_exists('active')) {
        function active($route)
        {
            return request()->routeIs($route)
                ? 'bg-gray-700/70 dark:bg-gray-600/50 text-white shadow-md'
                : 'text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 hover:text-white dark:hover:text-white';
        }
    }

    if (!function_exists('icon')) {
        function icon($route)
        {
            return request()->routeIs($route) ? 'text-yellow-300 dark:text-yellow-400' : 'text-gray-200 dark:text-gray-400';
        }
    }
@endphp

<aside id="sidebar"
    class="fixed lg:relative w-72 bg-gradient-to-b from-gray-800 to-gray-900 dark:from-dark-800 dark:to-dark-900 text-white flex flex-col shadow-xl transition-all duration-300 z-30 h-full top-0 left-0 -translate-x-full lg:translate-x-0">

    <!-- Tombol Close untuk Mobile -->
    <button id="closeSidebarBtn"
        class="absolute top-4 right-4 p-2 rounded-full bg-gray-700/50 hover:bg-gray-600/70 text-white lg:hidden transition-all z-10">
        <i class='bx bx-x text-2xl'></i>
    </button>

    <!-- Brand -->
    <div class="flex items-center gap-3 px-5 py-6 border-b border-gray-700/60 dark:border-dark-700">
        <div>
            <img src="{{ asset('/logo/logo-buana.png') }}" alt="Logo Buana" class="w-16 h-16 object-contain">
        </div>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">Buana<span
                    class="text-yellow-300 dark:text-yellow-400">Net</span></h1>
            <p class="text-xs text-gray-200 dark:text-gray-400 mt-0.5">Management System</p>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto scroll-smooth">
        @if (auth()->user()->role == 'cabang')
            <!-- Dashboard Cabang -->
            <a href="{{ route('cabang.dashboard') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('cabang.dashboard') }}">
                <i class='bx bxs-dashboard text-xl {{ icon('cabang.dashboard') }}'></i>
                <span class="font-medium">Dashboard Cabang</span>
            </a>

            <!-- Divider -->
            <div class="pt-6 mt-4 border-t border-gray-700/40 dark:border-dark-700"></div>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center w-full gap-3 px-4 py-3 rounded-xl text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200">
                    <i class='bx bx-log-out text-xl'></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        @else
            <!-- Dashboard -->
            <a href="{{ route('dashboard.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('dashboard.index') }}">
                <i class='bx bxs-dashboard text-xl {{ icon('dashboard.index') }}'></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Tagihan -->
            <a href="{{ route('invoice.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('invoice.*') }}">
                <i class='bx bxs-receipt text-xl {{ icon('invoice.*') }}'></i>
                <span class="font-medium">Tagihan</span>
            </a>

            <!-- Tagihan WiFi -->
            <a href="{{ route('tagihan-wifi.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('tagihan-wifi.*') }}">
                <i class='bx bx-wifi text-xl {{ icon('tagihan-wifi.*') }}'></i>
                <span class="font-medium">Tagihan WiFi</span>
            </a>

            <!-- Kasbon / Piutang -->
            <a href="{{ route('kasbon.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('kasbon.*') }}">
                <i class='bx bxs-credit-card text-xl {{ icon('kasbon.*') }}'></i>
                <span class="font-medium">Kasbon / Piutang</span>
            </a>

            <!-- Pelanggan -->
            <a href="{{ route('pelanggan.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('pelanggan.*') }}">
                <i class='bx bxs-user-detail text-xl {{ icon('pelanggan.*') }}'></i>
                <span class="font-medium">Pelanggan</span>
            </a>

            <!-- Produk -->
            <a href="{{ route('produk.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('produk.*') }}">
                <i class='bx bxs-package text-xl {{ icon('produk.*') }}'></i>
                <span class="font-medium">Produk</span>
            </a>

            {{-- <!-- Pembayaran -->
            <a href="{{ route('pembayaran.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('pembayaran.*') }}">
                <i class='bx bxs-bank text-xl {{ icon('pembayaran.*') }}'></i>
                <span class="font-medium">Pembayaran</span>
            </a> --}}


            <!-- Keuangan -->
            <a href="{{ route('kas.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('kas.*') }}">
                <i class='bx bxs-wallet text-xl {{ icon('kas.*') }}'></i>
                <span class="font-medium">Laporan Keuangan</span>
            </a>

            <!-- Divider -->
            <div class="pt-6 mt-4 border-t border-gray-700/40 dark:border-dark-700"></div>

            <!-- Laporan -->
            <a href="{{ route('laporan.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('laporan.*') }}">
                <i class='bx bxs-bar-chart-square text-xl {{ icon('laporan.*') }}'></i>
                <span class="font-medium">Laporan</span>
            </a>

            <!-- Absensi -->
            @php $absensiAktif = request()->routeIs('absensi.*') && !request()->routeIs('absensi.riwayat'); @endphp
            <a href="{{ route('absensi.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $absensiAktif ? active('absensi.*') : 'text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 hover:text-white dark:hover:text-white' }}">
                <i
                    class='bx bxs-calendar-check text-xl {{ $absensiAktif ? 'text-yellow-300 dark:text-yellow-400' : 'text-gray-200 dark:text-gray-400' }}'></i>
                <span class="font-medium">Absensi</span>
            </a>

            <!-- Riwayat Absensi -->
            @php $riwayatAktif = request()->routeIs('absensi.riwayat'); @endphp
            <a href="{{ route('absensi.riwayat') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $riwayatAktif ? active('absensi.riwayat') : 'text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 hover:text-white dark:hover:text-white' }}">
                <i
                    class='bx bx-history text-xl {{ $riwayatAktif ? 'text-yellow-300 dark:text-yellow-400' : 'text-gray-200 dark:text-gray-400' }}'></i>
                <span class="font-medium">Riwayat Absensi</span>
            </a>

            <!-- Cabang -->
            <a href="{{ route('cabang.index') }}"
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('cabang.*') }}">
                <i class='bx bxs-buildings text-xl {{ icon('cabang.*') }}'></i>
                <span class="font-medium">Cabang</span>
            </a>

            @if (auth()->user()->role == 'owner')
                <!-- Dashboard Pajak -->
                <a href="{{ route('dashboard.pajak') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('dashboard.pajak') }}">
                    <i class='bx bx-calculator text-xl {{ icon('dashboard.pajak') }}'></i>
                    <span class="font-medium">Dashboard Pajak</span>
                </a>

                <!-- Slip Gaji -->
                @php $slipGajiAktif = request()->routeIs('slip-gaji.*'); @endphp
                <a href="{{ route('slip-gaji.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $slipGajiAktif ? active('slip-gaji.*') : 'text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 hover:text-white dark:hover:text-white' }}">
                    <i
                        class='bx bx-receipt text-xl {{ $slipGajiAktif ? 'text-yellow-300 dark:text-yellow-400' : 'text-gray-200 dark:text-gray-400' }}'></i>
                    <span class="font-medium">Slip Gaji</span>
                </a>

                <!-- Repeater -->
                @php $repeaterAktif = request()->routeIs('repeater.*'); @endphp
                <a href="{{ route('repeater.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $repeaterAktif ? active('repeater.*') : 'text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 hover:text-white dark:hover:text-white' }}">
                    <i class='bx bx-wifi text-xl {{ $repeaterAktif ? 'text-yellow-300 dark:text-yellow-400' : 'text-gray-200 dark:text-gray-400' }}'></i>
                    <span class="font-medium">Repeater</span>
                </a>

                <!-- Operasional Perusahaan -->
                <a href="{{ route('biaya-operasional.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('biaya-operasional.*') }}">
                    <i class='bx bx-category text-xl {{ icon('biaya-operasional.*') }}'></i>
                    <span class="font-medium">Operasional Perusahaan</span>
                </a>

                <!-- Log Aktivitas -->
                <a href="{{ route('pengaturan.log') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ active('pengaturan.log') }}">
                    <i class='bx bx-history text-xl {{ icon('pengaturan.log') }}'></i>
                    <span class="font-medium">Log Aktivitas</span>
                </a>
            @endif

            <!-- Pengaturan (Dropdown) -->
            @php $pengaturanOpen = request()->routeIs('pengaturan.*'); @endphp
            <div>
                <button onclick="togglePengaturan(event)"
                    class="pengaturan-toggle flex items-center justify-between w-full gap-3 px-4 py-3 rounded-xl text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ $pengaturanOpen ? 'bg-gray-800/60 dark:bg-dark-700' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class='bx bx-cog text-xl'></i>
                        <span class="font-medium">Pengaturan</span>
                    </div>
                    <i
                        class='pengaturan-chevron bx bx-chevron-down text-lg transition-transform duration-200 {{ $pengaturanOpen ? 'rotate-180' : '' }}'></i>
                </button>
                <div class="pengaturan-submenu ml-4 mt-1 space-y-1 {{ $pengaturanOpen ? '' : 'hidden' }}">
                    <a href="{{ route('pengaturan.profil') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pengaturan.profil') }}">
                        <i class='bx bx-user text-lg'></i>
                        <span>Profil</span>
                    </a>
                    @if (auth()->user()->role == 'owner')
                        <a href="{{ route('pengaturan.perusahaan') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pengaturan.perusahaan') }}">
                            <i class='bx bx-building text-lg'></i>
                            <span>Perusahaan</span>
                        </a>
                        <a href="{{ route('pengaturan.paket') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pengaturan.paket') }}">
                            <i class='bx bx-wifi text-lg'></i>
                            <span>Paket WiFi</span>
                        </a>
                        <a href="{{ route('pengaturan.penanggung-jawab') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pengaturan.penanggung-jawab') }}">
                            <i class='bx bx-user-pin text-lg'></i>
                            <span>Penanggung Jawab</span>
                        </a>
                        <a href="{{ route('pegawai.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pegawai.*') }}">
                            <i class='bx bxs-user-account text-lg'></i>
                            <span>Manajemen Pegawai</span>
                        </a>
                        <a href="{{ route('akun.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('akun.*') }}">
                            <i class='bx bxs-user-circle text-lg'></i>
                            <span>Manajemen Akun</span>
                        </a>
                        <a href="{{ route('pengaturan.backup') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm text-gray-200 dark:text-gray-400 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200 {{ active('pengaturan.backup*') }}">
                            <i class='bx bx-cloud-upload text-lg'></i>
                            <span>Backup Database</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center w-full gap-3 px-4 py-3 rounded-xl text-gray-100 dark:text-gray-300 hover:bg-gray-800/60 dark:hover:bg-dark-700 transition-all duration-200">
                    <i class='bx bx-log-out text-xl'></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        @endif
    </nav>

    <!-- Footer sidebar dengan User Info & Theme Toggle -->
    <div class="p-4 border-t border-gray-700/40 dark:border-dark-700 space-y-3">
        <!-- User Info -->
        <div
            class="bg-gray-950/40 dark:bg-dark-800/60 p-3 rounded-xl text-xs text-gray-200 dark:text-gray-300 flex items-center gap-2">
            <i class='bx bx-user text-yellow-300 dark:text-yellow-400 text-lg'></i>
            <span class="flex-1">{{ auth()->user()->nama_lengkap }} • Online</span>
            <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
        </div>

        <!-- Theme Toggle Button -->
        <button onclick="toggleTheme()" id="sidebarThemeToggle"
            class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl bg-gray-950/40 dark:bg-dark-800/60 hover:bg-gray-900/60 dark:hover:bg-dark-700 transition-all duration-200 group">
            <div class="flex items-center gap-3">
                <i id="sidebarThemeIcon"
                    class='bx bx-moon text-yellow-300 dark:text-yellow-400 text-xl group-hover:scale-110 transition-transform'></i>
                <span id="sidebarThemeText" class="text-sm font-medium text-gray-100 dark:text-gray-300">Mode
                    Gelap</span>
            </div>
            <div class="relative">
                <div class="w-10 h-5 bg-gray-700/50 dark:bg-dark-600 rounded-full transition-colors">
                    <div id="sidebarThemeSlider"
                        class="absolute w-4 h-4 bg-yellow-300 dark:bg-blue-400 rounded-full transition-all duration-300 top-0.5 left-0.5 dark:left-5">
                    </div>
                </div>
            </div>
        </button>
    </div>
</aside>

<!-- Overlay untuk mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden transition-all duration-300"></div>

<script>
    // ========== MOBILE SIDEBAR TOGGLE SYSTEM ==========
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('closeSidebarBtn');

        // Fungsi untuk membuka sidebar
        function openSidebar() {
            if (sidebar) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden'; // Prevent scroll di belakang
            }
            if (overlay) {
                overlay.classList.remove('hidden');
            }
        }

        // Fungsi untuk menutup sidebar
        function closeSidebar() {
            if (sidebar) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                document.body.style.overflow = '';
            }
            if (overlay) {
                overlay.classList.add('hidden');
            }
        }

        // Toggle sidebar function (dipanggil dari header)
        window.toggleMobileSidebar = function() {
            if (sidebar && sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        };

        // Event listener untuk tombol close
        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }

        // Event listener untuk overlay (klik overlay menutup sidebar)
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Tutup sidebar saat resize ke desktop (biar konsisten)
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1024) {
                    // Di desktop, pastikan sidebar visible dan overlay hidden
                    if (sidebar) {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('lg:translate-x-0');
                    }
                    if (overlay) {
                        overlay.classList.add('hidden');
                    }
                    document.body.style.overflow = '';
                } else {
                    // Di mobile, reset ke hidden jika perlu
                    if (sidebar && !sidebar.classList.contains('-translate-x-full') && !sidebar
                        .classList.contains('lg:translate-x-0')) {
                        // Biarkan terbuka jika sedang terbuka
                    } else if (sidebar && window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                    }
                }
            }, 100);
        });

        // Tutup sidebar saat menekan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full') && window
                .innerWidth < 1024) {
                closeSidebar();
            }
        });

        // Auto close saat klik link di sidebar (mobile)
        if (sidebar) {
            const navLinks = sidebar.querySelectorAll('a, button[type="submit"]');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        // Kasih delay sedikit biar link sempat terklik
                        setTimeout(closeSidebar, 150);
                    }
                });
            });
        }
    })();

    // ========== PENGATURAN DROPDOWN TOGGLE ==========
    function togglePengaturan(event) {
        event.preventDefault();
        const btn = event.currentTarget;
        const submenu = btn.nextElementSibling;
        const chevron = btn.querySelector('.pengaturan-chevron');
        submenu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // ========== THEME TOGGLE SINKRONISASI ==========
    (function() {
        function updateSidebarThemeUI() {
            const isDark = document.documentElement.classList.contains('dark-mode');
            const icon = document.getElementById('sidebarThemeIcon');
            const text = document.getElementById('sidebarThemeText');
            const slider = document.getElementById('sidebarThemeSlider');

            if (icon) {
                if (isDark) {
                    icon.classList.remove('bx-moon');
                    icon.classList.add('bx-sun');
                } else {
                    icon.classList.remove('bx-sun');
                    icon.classList.add('bx-moon');
                }
            }

            if (text) {
                text.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
            }

            if (slider) {
                if (isDark) {
                    slider.classList.add('dark:left-5');
                } else {
                    slider.classList.remove('dark:left-5');
                }
            }
        }

        updateSidebarThemeUI();

        window.addEventListener('themeChanged', function(e) {
            updateSidebarThemeUI();
        });

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
                updateSidebarThemeUI();
            };
        }
    })();
</script>

<style>
    /* ========== SIDEBAR MOBILE STYLES ========== */
    /* Default: sidebar hidden di mobile */
    @media (max-width: 1023px) {
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 30;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        #sidebar.translate-x-0 {
            transform: translateX(0);
        }

        #sidebar.-translate-x-full {
            transform: translateX(-100%);
        }
    }

    /* Desktop: sidebar selalu visible */
    @media (min-width: 1024px) {
        #sidebar {
            transform: translateX(0) !important;
            position: relative;
        }

        #sidebarOverlay {
            display: none !important;
        }
    }

    /* Sembunyikan scrollbar tapi tetap fungsional */
    aside nav::-webkit-scrollbar {
        width: 4px;
    }

    aside nav::-webkit-scrollbar-track {
        background: rgba(107, 114, 128, 0.2);
        border-radius: 10px;
    }

    aside nav::-webkit-scrollbar-thumb {
        background: rgba(107, 114, 128, 0.5);
        border-radius: 10px;
    }

    aside nav::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.7);
    }

    /* Dark mode scrollbar */
    .dark-mode aside nav::-webkit-scrollbar-track {
        background: rgba(30, 30, 45, 0.5);
    }

    .dark-mode aside nav::-webkit-scrollbar-thumb {
        background: rgba(82, 82, 101, 0.7);
    }

    /* Transisi smooth */
    aside * {
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    }

    /* Hover effect untuk nav-link */
    .nav-link {
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 0;
        height: 100%;
        background: rgba(255, 255, 255, 0.08);
        transition: width 0.3s ease;
        z-index: -1;
    }

    .nav-link:hover::before {
        width: 100%;
    }

    /* Tombol theme toggle hover */
    #sidebarThemeToggle:hover {
        transform: translateY(-1px);
    }

    #sidebarThemeToggle:active {
        transform: translateY(0);
    }

    /* Overlay animation */
    #sidebarOverlay {
        transition: opacity 0.3s ease;
    }

    #sidebarOverlay:not(.hidden) {
        opacity: 1;
    }

    #sidebarOverlay.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }
</style>
