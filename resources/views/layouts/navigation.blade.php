{{-- Desktop Left Sidebar --}}
<aside class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 hidden md:flex flex-col py-6 justify-between sticky top-0 h-screen z-20 shrink-0 transition-all duration-300 ease-in-out"
       :class="sidebarOpen ? 'w-64 px-4' : 'w-20 px-0 items-center'">
    
    <div class="flex flex-col items-center gap-8 w-full">
        {{-- Brand Logo & Toggle --}}
        <div class="flex items-center w-full transition-all duration-300"
             :class="sidebarOpen ? 'justify-between px-2' : 'justify-center'">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo-dpmd-bangkalan.png') }}" alt="Logo DPMD" class="w-8 h-8 object-contain shrink-0">
                <span class="font-extrabold text-indigo-650 dark:text-indigo-400 text-base uppercase tracking-wider font-jakarta whitespace-nowrap overflow-hidden transition-all duration-300"
                      x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                    BANGEDI
                </span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebar_open', sidebarOpen)"
                    class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hidden md:block"
                    title="Toggle Sidebar">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </div>

        {{-- Nav Icons --}}
        <nav class="flex flex-col gap-2 w-full" :class="sidebarOpen ? 'px-2' : 'items-center'">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
               :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
               title="Dashboard">
                <i class="fa-solid fa-chart-pie text-lg shrink-0"></i>
                <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            {{-- PPK: Pengadaan & Administrasi --}}
            @if(Auth::user()->jabatan_aktif === 'PPK')
                {{-- Group: Pengadaan --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Pengadaan
                    </span>
                </div>
                <a href="{{ route('paket.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('paket.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Daftar Paket">
                    <i class="fa-solid fa-folder-open text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Daftar Paket</span>
                </a>
                <a href="{{ route('berita-acara.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('berita-acara.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Berita Acara">
                    <i class="fa-solid fa-file-invoice text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Berita Acara</span>
                </a>

                {{-- Group: Administrasi --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Administrasi
                    </span>
                </div>
                <a href="{{ route('transfers.create') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('transfers.create') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Transfer Jabatan">
                    <i class="fa-solid fa-right-left text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Transfer Jabatan</span>
                </a>
            @endif

            {{-- PP: Pengadaan & Administrasi --}}
            @if(Auth::user()->jabatan_aktif === 'PP')
                {{-- Group: Pengadaan --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Pengadaan
                    </span>
                </div>
                <a href="{{ route('paket-review.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('paket-review.*') || request()->routeIs('paket-bypass.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Daftar Paket">
                    <i class="fa-solid fa-folder-open text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Daftar Paket</span>
                </a>
                <a href="{{ route('berita-acara.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('berita-acara.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Berita Acara">
                    <i class="fa-solid fa-file-invoice text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Berita Acara</span>
                </a>

                {{-- Group: Administrasi --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Administrasi
                    </span>
                </div>
                <a href="{{ route('transfers.create') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('transfers.create') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Transfer Jabatan">
                    <i class="fa-solid fa-right-left text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Transfer Jabatan</span>
                </a>
            @endif

            {{-- Admin Submenus --}}
            @if(Auth::user()->jabatan_aktif === 'admin')
                {{-- Group: Pengadaan --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Pengadaan
                    </span>
                </div>
                <a href="{{ route('admin.paket.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('admin.paket.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Daftar Paket">
                    <i class="fa-solid fa-folder-open text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Daftar Paket</span>
                </a>
                <a href="{{ route('berita-acara.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('berita-acara.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Berita Acara">
                    <i class="fa-solid fa-file-invoice text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Berita Acara</span>
                </a>

                {{-- Group: Administrator --}}
                <div class="w-full flex flex-col gap-1 mt-3" x-show="sidebarOpen">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-4 mb-1">
                        Administrator
                    </span>
                </div>
                <a href="{{ route('admin.users.verification') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('admin.users.verification') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Verifikasi Akun">
                    <i class="fa-solid fa-user-check text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Verifikasi Akun</span>
                </a>
                <a href="{{ route('admin.users.reset-password') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('admin.users.reset-password') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Reset Password">
                    <i class="fa-solid fa-key text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Reset Password</span>
                </a>
                <a href="{{ route('admin.transfers.index') }}"
                   class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                          {{ request()->routeIs('admin.transfers.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
                   :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
                   title="Mutasi Paket">
                    <i class="fa-solid fa-right-left text-lg shrink-0"></i>
                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Mutasi Paket</span>
                </a>
            @endif

            {{-- Profil --}}
            <a href="{{ route('profile.edit') }}"
               class="h-11 rounded-xl flex items-center transition hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200
                      {{ request()->routeIs('profile.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}"
               :class="sidebarOpen ? 'w-full px-4 gap-3 justify-start' : 'w-11 justify-center'"
               title="Profil">
                <i class="fa-solid fa-gear text-lg shrink-0"></i>
                <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Profil Saya</span>
            </a>
        </nav>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" class="w-full flex" :class="sidebarOpen ? 'px-2' : 'justify-center'">
        @csrf
        <button type="submit"
                class="h-11 rounded-xl flex items-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition w-full"
                :class="sidebarOpen ? 'px-4 gap-3 justify-start' : 'w-11 justify-center'"
                title="Logout">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg shrink-0"></i>
            <span class="text-sm font-semibold whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>Logout</span>
        </button>
    </form>
</aside>

{{-- Mobile Bottom Navigation Bar --}}
<div class="fixed bottom-0 left-0 z-30 w-full bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex md:hidden justify-around items-center h-16 px-2">

    <a href="{{ route('dashboard') }}"
       class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
        <i class="fa-solid fa-chart-pie text-lg"></i>
        <span class="text-[9px] font-semibold font-jakarta">Dashboard</span>
    </a>

    @if(Auth::user()->jabatan_aktif === 'PPK')
        <a href="{{ route('paket.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('paket.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
            <i class="fa-solid fa-folder-open text-lg"></i>
            <span class="text-[9px] font-semibold font-jakarta">Paket</span>
        </a>
    @endif

    @if(Auth::user()->jabatan_aktif === 'PP')
        <a href="{{ route('paket-review.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('paket-review.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
            <i class="fa-solid fa-folder-magnifying-glass text-lg"></i>
            <span class="text-[9px] font-semibold font-jakarta">Review</span>
        </a>
    @endif

    @if(Auth::user()->jabatan_aktif === 'admin')
        <a href="{{ route('admin.users.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
            <i class="fa-solid fa-users-gear text-lg"></i>
            <span class="text-[9px] font-semibold font-jakarta">Users</span>
        </a>
        <a href="{{ route('admin.transfers.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('admin.transfers.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
            <i class="fa-solid fa-right-left text-lg"></i>
            <span class="text-[9px] font-semibold font-jakarta">Mutasi</span>
        </a>
    @endif

    <a href="{{ route('profile.edit') }}"
       class="flex flex-col items-center justify-center gap-0.5 px-3 {{ request()->routeIs('profile.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
        <i class="fa-solid fa-gear text-lg"></i>
        <span class="text-[9px] font-semibold font-jakarta">Profil</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="flex">
        @csrf
        <button type="submit" class="flex flex-col items-center justify-center gap-0.5 px-3 text-rose-500">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
            <span class="text-[9px] font-semibold font-jakarta">Logout</span>
        </button>
    </form>
</div>
