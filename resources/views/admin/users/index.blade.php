<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengguna & Verifikasi Akun') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Alert -->
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Gagal!</span> {{ session('error') }}
                </div>
            @endif

            <!-- Section 1: Pending Requests -->
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">
                    {{ __('Permintaan Registrasi Baru (Pending)') }}
                </h3>

                @if($pendingUsers->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 py-4">Tidak ada permintaan registrasi yang tertunda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">OPD / Sub-Unit</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nomor SK</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jabatan Diajukan</th>
                                    <th class="px-6 py-3 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pendingUsers as $user)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-mono">{{ $user->nip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-semibold">{{ $user->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-550 dark:text-gray-300">
                                            <div>{{ $user->opd }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->sub_unit_opd }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-550 dark:text-gray-300 font-mono text-xs">{{ $user->sk_nomor }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $user->jabatan_aktif }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">
                                                    Approve
                                                </button>
                                            </form>
                                            <!-- Reject Form -->
                                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini? Data akun akan dihapus permanen.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-650 hover:bg-rose-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">
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

            <!-- Section 2: Active Accounts -->
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">
                    {{ __('Daftar Pengguna Aktif') }}
                </h3>

                @if($activeUsers->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 py-4">Tidak ada pengguna aktif lain.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">OPD</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jabatan Aktif</th>
                                    <th class="px-6 py-3 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($activeUsers as $user)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-mono">{{ $user->nip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-semibold">{{ $user->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-550 dark:text-gray-400">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-550 dark:text-gray-300">
                                            <div>{{ $user->opd }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->sub_unit_opd }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- Update Role Form -->
                                            <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="jabatan_aktif" class="text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1">
                                                    <option value="admin" {{ $user->jabatan_aktif == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="PPK" {{ $user->jabatan_aktif == 'PPK' ? 'selected' : '' }}>PPK</option>
                                                    <option value="PP" {{ $user->jabatan_aktif == 'PP' ? 'selected' : '' }}>PP</option>
                                                </select>
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded text-xs transition duration-150">
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <!-- Generate Reset Password Token -->
                                            <form action="{{ route('admin.users.reset-token', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengirim token reset password ke email user?')">
                                                @csrf
                                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">
                                                    Reset Password
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
