<!DOCTYPE html>
<html lang="id" class="light-mode">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'BuanaNet Pro')</title>

    <!-- Tailwind + Konfigurasi Custom untuk Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Dark mode palette - abu profesional
                        dark: {
                            50: '#f7f7f8',
                            100: '#ececef',
                            200: '#d7d7df',
                            300: '#b5b5c0',
                            400: '#8c8c9e',
                            500: '#6b6b80',
                            600: '#525265',
                            700: '#3a3a4a',
                            800: '#1f1f2b',
                            900: '#12121a',
                            950: '#0a0a0f',
                        },
                        // Light mode accent - professional gray
                        brand: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'Segoe UI', 'sans-serif'],
                    },
                    boxShadow: {
                        'card-light': '0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)',
                        'card-dark': '0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3)',
                        'pro': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-out',
                        'slide-in': 'slideIn 0.25s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideIn: {
                            '0%': {
                                transform: 'translateX(-10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateX(0)',
                                opacity: '1'
                            },
                        },
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* custom scrollbar untuk kedua tema */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .light-mode ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .light-mode ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark-mode ::-webkit-scrollbar-track {
            background: #1f1f2b;
            border-radius: 10px;
        }

        .dark-mode ::-webkit-scrollbar-thumb {
            background: #525265;
            border-radius: 10px;
        }

        .dark-mode ::-webkit-scrollbar-thumb:hover {
            background: #6b6b80;
        }

        /* transisi smooth global */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .sidebar-transition {
            transition: transform 0.3s ease, width 0.3s ease;
        }
    </style>

    @include('components.css')
</head>

<body class="font-sans antialiased transition-colors duration-200 bg-gray-50 dark:bg-dark-950">
    <div class="flex h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        @include('components.sidebar')

        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-dark-900 transition-colors duration-200">
            <div class="sticky top-0 z-20">
                @include('components.header')
            </div>

            {{-- CONTENT WRAPPER dengan padding & card styling --}}
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Theme Toggle Script & Initialization --}}
    <script>
        // theme management system - dark mode abu profesional, light mode putih bersih
        (function() {
            const THEME_KEY = 'buana_theme_preference';
            // cek preferensi tersimpan atau system preference
            const getPreferredTheme = () => {
                const saved = localStorage.getItem(THEME_KEY);
                if (saved === 'dark' || saved === 'light') return saved;
                // fallback ke system preference
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            const applyTheme = (theme) => {
                const root = document.documentElement;
                if (theme === 'dark') {
                    root.classList.remove('light-mode');
                    root.classList.add('dark-mode');
                    root.classList.add('dark');
                    document.body.classList.add('dark-mode');
                    document.body.classList.remove('light-mode');
                } else {
                    root.classList.remove('dark-mode');
                    root.classList.add('light-mode');
                    root.classList.remove('dark');
                    document.body.classList.remove('dark-mode');
                    document.body.classList.add('light-mode');
                }
                localStorage.setItem(THEME_KEY, theme);

                // dispatch event untuk component lain yang butuh update tema
                window.dispatchEvent(new CustomEvent('themeChanged', {
                    detail: {
                        theme
                    }
                }));

                // update select2 jika ada (menyesuaikan background)
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('.select2').each(function() {
                        $(this).trigger('change');
                    });
                }
            };

            const currentTheme = getPreferredTheme();
            applyTheme(currentTheme);

            // listen tombol toggle dari header (pastikan ada elemen dengan id themeToggle)
            window.toggleTheme = function() {
                const newTheme = document.documentElement.classList.contains('dark-mode') ? 'light' : 'dark';
                applyTheme(newTheme);
                // optional: update icon di header jika diperlukan
                const toggleBtn = document.getElementById('themeToggleBtn');
                if (toggleBtn) {
                    const icon = toggleBtn.querySelector('i');
                    if (icon) {
                        if (newTheme === 'dark') {
                            icon.classList.remove('bx-moon');
                            icon.classList.add('bx-sun');
                            toggleBtn.setAttribute('title', 'Light Mode');
                        } else {
                            icon.classList.remove('bx-sun');
                            icon.classList.add('bx-moon');
                            toggleBtn.setAttribute('title', 'Dark Mode');
                        }
                    }
                }
            };
        })();

        // fungsi money toggle yang sudah ada
        let moneyVisible = true;

        function toggleMoney() {
            moneyVisible = !moneyVisible;
            const els = document.querySelectorAll('.money-value');
            const icon = document.getElementById('moneyToggleIcon');
            const label = document.getElementById('moneyToggleLabel');
            els.forEach(el => {
                el.textContent = moneyVisible ? el.dataset.amount : 'Rp ****';
            });
            if (icon && label) {
                if (moneyVisible) {
                    icon.classList.replace('bx-hide', 'bx-show');
                    label.textContent = 'Sembunyikan Jumlah';
                } else {
                    icon.classList.replace('bx-show', 'bx-hide');
                    label.textContent = 'Tampilkan Jumlah';
                }
            }
        }
    </script>

    {{-- FLOWBITE, SELECT2 INIT --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // init select2 dengan tema menyesuaikan mode (agar terlihat pro)
            if ($.fn.select2) {
                $('.select2').each(function() {
                    const isDark = document.documentElement.classList.contains('dark-mode');
                    $(this).select2({
                        theme: isDark ? 'classic' : 'default',
                        dropdownAutoWidth: true,
                        width: 'resolve'
                    });
                });
            }

            // // tambahkan tombol theme toggle secara dinamis jika belum ada di header
            // // (asumsi header belum punya tombol, kita inject dengan style minimal)
            // const ensureThemeButton = () => {
            //     if (!document.getElementById('themeToggleBtn')) {
            //         const headerRight = document.querySelector(
            //             '.header-right-area, .flex.items-center.gap-3, .ml-auto');
            //         if (headerRight) {
            //             const btn = document.createElement('button');
            //             btn.id = 'themeToggleBtn';
            //             btn.className =
            //                 'p-2 rounded-full transition-all duration-200 hover:bg-gray-200 dark:hover:bg-dark-700 text-gray-700 dark:text-gray-200';
            //             btn.setAttribute('aria-label', 'Toggle Theme');
            //             btn.onclick = () => window.toggleTheme();
            //             const isDark = document.documentElement.classList.contains('dark-mode');
            //             btn.innerHTML = isDark ? '<i class="bx bx-sun text-xl"></i>' :
            //                 '<i class="bx bx-moon text-xl"></i>';
            //             headerRight.prepend(btn);
            //         }
            //     }
            // };
            // setTimeout(ensureThemeButton, 100);

            // update select2 saat tema berubah
            window.addEventListener('themeChanged', function(e) {
                const isDark = e.detail.theme === 'dark';
                $('.select2').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                    $(this).select2({
                        theme: isDark ? 'classic' : 'default',
                        dropdownAutoWidth: true,
                        width: 'resolve'
                    });
                });
                // update tombol icon jika ada
                const btn = document.getElementById('themeToggleBtn');
                if (btn) {
                    const icon = btn.querySelector('i');
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
            });
        });
    </script>

    @stack('scripts')
    @include('components.alert')
</body>

</html>
