<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Mutasi & Transfer Tugas Paket') }}
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

            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">
                    {{ __('Daftar Pengajuan Mutasi / Transfer Kepemilikan Paket') }}
                </h3>

                @if($transfers->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 py-4">Belum ada pengajuan transfer tugas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paket</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dari Pejabat</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ke Pejabat</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alasan Pengaju</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi / Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transfers as $transfer)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-gray-700">
                                        <!-- Paket info -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100 max-w-[200px] truncate" title="{{ $transfer->paket->nama_paket }}">
                                                {{ $transfer->paket->nama_paket }}
                                            </div>
                                            <div class="text-xs text-gray-400 font-mono">{{ $transfer->paket->kode_rup }}</div>
                                        </td>
                                        
                                        <!-- Dari Pejabat -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-gray-200">{{ $transfer->dariUser->nama }}</div>
                                            <div class="text-xs text-gray-400">NIP: {{ $transfer->dariUser->nip }}</div>
                                        </td>
                                        
                                        <!-- Ke Pejabat -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-gray-200">{{ $transfer->keUser->nama }}</div>
                                            <div class="text-xs text-gray-400">NIP: {{ $transfer->keUser->nip }}</div>
                                        </td>
                                        
                                        <!-- Tipe -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $transfer->tipe_transfer }}
                                            </span>
                                        </td>
                                        
                                        <!-- Alasan -->
                                        <td class="px-4 py-4 max-w-[200px] truncate" title="{{ $transfer->alasan }}">
                                            <span class="text-xs text-gray-650 dark:text-gray-300">{{ $transfer->alasan }}</span>
                                        </td>
                                        
                                        <!-- Status Badge -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusClasses = [
                                                    'menunggu' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                    'ditolak' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                                                ];
                                                $badgeClass = $statusClasses[$transfer->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                                {{ strtoupper($transfer->status) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Aksi / Detail -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-xs">
                                            @if($transfer->status === 'menunggu')
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Approve -->
                                                    <form action="{{ route('admin.transfers.approve', $transfer) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-3 rounded transition duration-150">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Reject Button -->
                                                    <button type="button" onclick="toggleRejectForm({{ $transfer->id }})" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-1 px-3 rounded transition duration-150">
                                                        Reject
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-[11px] text-gray-500 dark:text-gray-400 text-left">
                                                    <div>Diproses oleh: <span class="font-semibold">{{ $transfer->disetujuiOleh->nama ?? 'Sistem' }}</span></div>
                                                    @if($transfer->status === 'ditolak')
                                                        <div class="text-rose-500 italic max-w-[180px] truncate" title="{{ $transfer->catatan_admin }}">Alasan: {{ $transfer->catatan_admin }}</div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Inline Reject Input Form -->
                                    @if($transfer->status === 'menunggu')
                                        <tr id="reject-row-{{ $transfer->id }}" class="hidden bg-amber-50/20 dark:bg-gray-750">
                                            <td colspan="7" class="px-6 py-3">
                                                <form action="{{ route('admin.transfers.reject', $transfer) }}" method="POST" class="flex items-center space-x-3">
                                                    @csrf
                                                    <x-input-label for="catatan_admin_{{ $transfer->id }}" :value="__('Alasan Penolakan:')" class="text-xs shrink-0 font-semibold" />
                                                    <input type="text" id="catatan_admin_{{ $transfer->id }}" name="catatan_admin" required minlength="5" placeholder="Tuliskan catatan alasan penolakan..." class="flex-1 text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1" />
                                                    <button type="submit" class="bg-rose-650 hover:bg-rose-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150">
                                                        Kirim Penolakan
                                                    </button>
                                                    <button type="button" onclick="toggleRejectForm({{ $transfer->id }})" class="text-xs text-gray-500 hover:underline">
                                                        Batal
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Toggle Javascript -->
    <script>
        function toggleRejectForm(id) {
            const row = document.getElementById('reject-row-' + id);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
