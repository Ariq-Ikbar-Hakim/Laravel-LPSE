<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BANGEDI') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/logo-dpmd-bangkalan.png') }}">
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome & Chart.js CDNs -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-150 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: localStorage.getItem('sidebar_open') !== 'false' }">
            <!-- Left Sidebar / Bottom Mobile Nav -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 pb-16 md:pb-0">
                <!-- Sticky Top Header -->
                <header class="h-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 md:px-8 flex items-center justify-between sticky top-0 z-10">
                    <!-- Left: Search or brand -->
                    <div class="flex items-center gap-3 flex-1 max-w-xs">
                        <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebar_open', sidebarOpen)"
                                class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hidden md:block"
                                title="Toggle Sidebar">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                        <div class="relative w-full hidden sm:block">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </span>
                            <input type="text" placeholder="Cari paket, user..." class="w-full pl-11 pr-4 py-2 rounded-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-800 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 text-slate-800 dark:text-white transition">
                        </div>
                        <!-- Brand text on mobile -->
                        <div class="sm:hidden flex items-center gap-2">
                            <img src="{{ asset('assets/logo-dpmd-bangkalan.png') }}" alt="Logo DPMD" class="w-7 h-7 object-contain">
                            <span class="font-extrabold text-indigo-600 dark:text-indigo-400 text-base uppercase tracking-wider font-jakarta">BANGEDI</span>
                        </div>
                    </div>

                    <!-- Right: Date, Settings, Avatar -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-full text-slate-600 dark:text-slate-400 hidden sm:inline-block">
                            {{ now()->isoFormat('dddd, D MMM') }}
                        </span>
                        
                        <!-- Dark Mode Toggle Button -->
                        <button onclick="toggleDarkMode()" 
                                class="w-10 h-10 rounded-full border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                title="Toggle Mode Gelap/Terang">
                            <i id="theme-toggle-icon" class="fa-solid fa-moon"></i>
                        </button>

                        <a href="{{ route('profile.edit') }}"
                           class="w-10 h-10 rounded-full border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-55 dark:hover:bg-slate-800 transition relative"
                           title="Pengaturan Profil">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                        <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
                            @if(Auth::user()->foto_profil)
                                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="{{ Auth::user()->nama }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/20">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 ring-2 ring-indigo-500/10">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Page Heading (Breeze $header slot) -->
                @isset($header)
                    <div class="bg-white dark:bg-gray-800 border-b border-slate-200 dark:border-gray-700 py-4 px-8 font-jakarta">
                        <div class="font-bold text-sm text-slate-800 dark:text-gray-250 leading-tight">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            function updateThemeIcon() {
                const icon = document.getElementById('theme-toggle-icon');
                if (icon) {
                    if (document.documentElement.classList.contains('dark')) {
                        icon.className = 'fa-solid fa-sun text-base text-amber-400';
                    } else {
                        icon.className = 'fa-solid fa-moon text-base text-slate-550';
                    }
                }
            }

            function toggleDarkMode() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateThemeIcon();
            }

            // Jalankan segera setelah DOM siap
            document.addEventListener('DOMContentLoaded', updateThemeIcon);
        </script>
    </body>
</html>
