<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Reset Password Pengguna</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kirimkan email berisi link reset kata sandi baru untuk pengguna aktif yang lupa password.</p>
                </div>
            </div>

            <!-- Success/Error Alert -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 flex items-center gap-3 text-emerald-800 dark:text-emerald-355 text-sm" role="alert">
                    <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                    <span><strong class="font-bold">Berhasil!</strong> {{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 flex items-center gap-3 text-rose-800 dark:text-rose-355 text-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
                    <span><strong class="font-bold">Gagal!</strong> {{ session('error') }}</span>
                </div>
            @endif

            <!-- Search Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
                <form action="{{ route('admin.users.reset-password') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..." 
                               class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.reset-password') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                    {{ __('Daftar Pengguna Aktif') }}
                </h3>

                @if($users->isEmpty())
                    <p class="text-sm text-slate-400 dark:text-slate-500 py-4 italic text-center">Tidak ada pengguna aktif ditemukan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                                    <th class="p-4 pl-6">NIP</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">OPD</th>
                                    <th class="p-4">Jabatan Aktif</th>
                                    <th class="p-4 pr-6 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-650 dark:text-slate-300 font-medium">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4 pl-6 font-mono text-xs">{{ $user->nip }}</td>
                                        <td class="p-4 font-semibold text-slate-900 dark:text-white">{{ $user->nama }}</td>
                                        <td class="p-4 text-xs">{{ $user->email }}</td>
                                        <td class="p-4">
                                            <div>{{ $user->opd }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500 font-normal">{{ $user->sub_unit_opd }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                                {{ $user->jabatan_aktif === 'PPK' ? 'bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400' : 'bg-violet-100 dark:bg-violet-950/40 text-violet-700 dark:text-violet-400' }}">
                                                {{ $user->jabatan_aktif }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            <!-- Generate Reset Password Token -->
                                            <form action="{{ route('admin.users.reset-token', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengirim token reset password ke email user?')">
                                                @csrf
                                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-1.5 px-4 rounded-xl text-xs transition duration-150 shadow-sm">
                                                    Reset Password
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
