<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Usulan Paket Pengadaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif

            <!-- Top Actions -->
            <div class="flex justify-between items-center">
                <!-- Status Tab Filter -->
                <div class="flex space-x-2 border-b dark:border-gray-700 pb-px">
                    @foreach(['all' => 'Semua', 'draft' => 'Draft', 'dikirim' => 'Dikirim', 'perlu_revisi' => 'Perlu Revisi', 'disetujui' => 'Disetujui', 'selesai' => 'Selesai'] as $key => $label)
                        <a href="{{ route('paket.index', ['status' => $key]) }}" class="px-4 py-2 text-sm font-medium transition-colors duration-150 {{ $status === $key ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('paket.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-750 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    + Buat Paket Baru
                </a>
            </div>

            <!-- Paket Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($paket->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 p-8 text-center">Tidak ada paket pengadaan ditemukan dengan status ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode RUP</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Paket</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pagu Anggaran</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($paket as $item)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-mono text-xs">{{ $item->kode_rup }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-semibold">{{ $item->nama_paket }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-550 dark:text-gray-300">Rp {{ number_format($item->pagu, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-350',
                                                    'dikirim' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                                    'kaji_ulang' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    'perlu_revisi' => 'bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                                    'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    'selesai' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                                ];
                                                $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                                {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                            <a href="{{ route('paket.show', $item) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Detail</a>
                                            @if($item->status === 'draft')
                                                <form action="{{ route('paket.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft paket ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-750 border-t dark:border-gray-700">
                        {{ $paket->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
