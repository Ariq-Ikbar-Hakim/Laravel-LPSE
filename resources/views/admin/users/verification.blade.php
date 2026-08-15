<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Verifikasi Akun Baru</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Approve atau reject pengajuan registrasi dari pejabat PPK dan PP baru.</p>
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

            <!-- Table Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                    {{ __('Antrian Persetujuan Registrasi') }}
                </h3>

                @if($pendingUsers->isEmpty())
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="fa-solid fa-user-check text-2xl"></i>
                        </div>
                        <p class="text-sm text-slate-400 dark:text-slate-500 italic">Tidak ada permintaan registrasi yang tertunda saat ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                                    <th class="p-4 pl-6">NIP</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">OPD / Unit Kerja</th>
                                    <th class="p-4">Nomor Telepon</th>
                                    <th class="p-4">Jabatan</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-650 dark:text-slate-300 font-medium">
                                @foreach($pendingUsers as $user)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4 pl-6 font-mono text-xs">{{ $user->nip }}</td>
                                        <td class="p-4 font-semibold text-slate-900 dark:text-white">{{ $user->nama }}</td>
                                        <td class="p-4 text-xs">{{ $user->email }}</td>
                                        <td class="p-4">
                                            <div>{{ $user->opd }}</div>
                                        </td>
                                        <td class="p-4 text-xs font-mono">{{ $user->no_telp }}</td>
                                        <td class="p-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400">
                                                {{ $user->jabatan_aktif }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6 text-center space-x-2 whitespace-nowrap">
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-3.5 rounded-xl text-xs transition duration-150">
                                                    Approve
                                                </button>
                                            </form>
                                            <!-- Reject Form -->
                                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini? Data akun akan dihapus permanen.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-1.5 px-3.5 rounded-xl text-xs transition duration-150">
                                                    Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
