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
                    {{ __('Daftar Pengajuan Mutasi / Swap Jabatan & Paket') }}
                </h3>

                @if($transfers->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 py-4">Belum ada pengajuan transfer tugas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Judul Mutasi / Paket</th>
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
                                        <!-- Judul Mutasi / Paket -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <button type="button" onclick="showTransferDetail({
                                                dari_nama: '{{ addslashes($transfer->dariUser->nama) }}',
                                                dari_nip: '{{ $transfer->dariUser->nip }}',
                                                dari_role: '{{ $transfer->tipe_transfer }}',
                                                ke_nama: '{{ addslashes($transfer->keUser->nama) }}',
                                                ke_nip: '{{ $transfer->keUser->nip }}',
                                                ke_role: '{{ $transfer->keUser->jabatan_aktif }}',
                                                status: '{{ $transfer->status }}',
                                                tanggal: '{{ $transfer->created_at->format('d M Y, H:i') }}',
                                                alasan: '{{ addslashes($transfer->alasan) }}',
                                                paket_nama: '{{ $transfer->paket ? addslashes($transfer->paket->nama_paket) : "" }}',
                                                paket_rup: '{{ $transfer->paket ? $transfer->paket->kode_rup : "" }}',
                                                admin_nama: '{{ $transfer->disetujuiOleh ? addslashes($transfer->disetujuiOleh->nama) : "" }}',
                                                catatan_admin: '{{ $transfer->catatan_admin ? addslashes($transfer->catatan_admin) : "" }}'
                                            })" class="text-left cursor-pointer hover:underline focus:outline-none">
                                                @if($transfer->paket)
                                                    <div class="font-semibold text-gray-900 dark:text-gray-100 max-w-[220px] truncate" title="{{ $transfer->paket->nama_paket }}">
                                                        {{ $transfer->paket->nama_paket }}
                                                    </div>
                                                    <div class="text-xs text-gray-400 font-mono">{{ $transfer->paket->kode_rup }}</div>
                                                @else
                                                    <div class="font-bold text-indigo-600 dark:text-indigo-400">
                                                        SWAP JABATAN & PERAN
                                                    </div>
                                                    <div class="text-xs text-slate-400">Serah terima seluruh paket tugas</div>
                                                @endif
                                            </button>
                                        </td>
                                        
                                        <!-- Dari Pejabat -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-gray-200 font-semibold">{{ $transfer->dariUser->nama }}</div>
                                            <div class="text-xs text-gray-400">NIP: {{ $transfer->dariUser->nip }}</div>
                                        </td>
                                        
                                        <!-- Ke Pejabat -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-gray-200 font-semibold">{{ $transfer->keUser->nama }}</div>
                                            <div class="text-xs text-gray-400">NIP: {{ $transfer->keUser->nip }}</div>
                                        </td>
                                        
                                        <!-- Tipe -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $transfer->tipe_transfer }}
                                            </span>
                                        </td>
                                        
                                        <!-- Alasan -->
                                        <td class="px-4 py-4 max-w-[180px] truncate" title="{{ $transfer->alasan }}">
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
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Detail Button -->
                                                <button type="button" onclick="showTransferDetail({
                                                    dari_nama: '{{ addslashes($transfer->dariUser->nama) }}',
                                                    dari_nip: '{{ $transfer->dariUser->nip }}',
                                                    dari_role: '{{ $transfer->tipe_transfer }}',
                                                    ke_nama: '{{ addslashes($transfer->keUser->nama) }}',
                                                    ke_nip: '{{ $transfer->keUser->nip }}',
                                                    ke_role: '{{ $transfer->keUser->jabatan_aktif }}',
                                                    status: '{{ $transfer->status }}',
                                                    tanggal: '{{ $transfer->created_at->format('d M Y, H:i') }}',
                                                    alasan: '{{ addslashes($transfer->alasan) }}',
                                                    paket_nama: '{{ $transfer->paket ? addslashes($transfer->paket->nama_paket) : '' }}',
                                                    paket_rup: '{{ $transfer->paket ? $transfer->paket->kode_rup : '' }}',
                                                    admin_nama: '{{ $transfer->disetujuiOleh ? addslashes($transfer->disetujuiOleh->nama) : '' }}',
                                                    catatan_admin: '{{ $transfer->catatan_admin ? addslashes($transfer->catatan_admin) : '' }}'
                                                })" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded-lg transition duration-150 cursor-pointer shadow-sm">
                                                    Detail
                                                </button>

                                                @if($transfer->status === 'menunggu')
                                                    <!-- Approve -->
                                                    <form action="{{ route('admin.transfers.approve', $transfer) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui swap jabatan & seluruh paket tugas ini?')">
                                                        @csrf
                                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-3 rounded-lg transition duration-150 cursor-pointer shadow-sm">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Reject Button -->
                                                    <button type="button" onclick="toggleRejectForm({{ $transfer->id }})" class="bg-rose-650 hover:bg-rose-700 text-white font-bold py-1 px-3 rounded-lg transition duration-150 cursor-pointer shadow-sm">
                                                        Reject
                                                    </button>
                                                @endif
                                            </div>

                                            @if($transfer->status !== 'menunggu')
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1.5 text-center">
                                                    Diproses oleh: <span class="font-semibold">{{ $transfer->disetujuiOleh->nama ?? 'Sistem' }}</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Inline Reject Input Form -->
                                    @if($transfer->status === 'menunggu')
                                        <tr id="reject-row-{{ $transfer->id }}" class="hidden bg-amber-50/20 dark:bg-slate-900">
                                            <td colspan="7" class="px-6 py-3">
                                                <form action="{{ route('admin.transfers.reject', $transfer) }}" method="POST" class="flex items-center space-x-3">
                                                    @csrf
                                                    <x-input-label for="catatan_admin_{{ $transfer->id }}" :value="__('Alasan Penolakan:')" class="text-xs shrink-0 font-semibold" />
                                                    <input type="text" id="catatan_admin_{{ $transfer->id }}" name="catatan_admin" placeholder="Tuliskan catatan alasan penolakan (opsional)..." class="flex-1 text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1" />
                                                    <button type="submit" class="bg-rose-650 hover:bg-rose-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150 cursor-pointer">
                                                        Kirim Penolakan
                                                    </button>
                                                    <button type="button" onclick="toggleRejectForm({{ $transfer->id }})" class="text-xs text-gray-500 hover:underline cursor-pointer">
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

    <!-- Modal Detail Mutasi / Swap -->
    <div id="detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-opacity" onclick="closeTransferModal()"></div>

            <!-- Modal Content -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white" id="modal-title">
                        Detail Pengajuan Swap Jabatan & Paket
                    </h3>
                    <button type="button" onclick="closeTransferModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4 text-xs">
                    <!-- Status & Tanggal -->
                    <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block mb-0.5 text-[10px] uppercase font-bold">Status Pengajuan</span>
                            <span id="modal-status" class="px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-[10px]">
                                -
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block mb-0.5 text-[10px] uppercase font-bold">Tanggal Pengajuan</span>
                            <span id="modal-tanggal" class="font-semibold text-slate-850 dark:text-slate-250">-</span>
                        </div>
                    </div>

                    <!-- Pihak Pengaju & Penerima -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block mb-1 text-[10px] uppercase font-bold">Dari Pejabat (Pengaju)</span>
                            <div id="modal-dari-nama" class="font-bold text-slate-900 dark:text-white">-</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">NIP: <span id="modal-dari-nip" class="font-mono font-semibold">-</span></div>
                            <div class="mt-2"><span id="modal-dari-role" class="px-2 py-0.5 rounded bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 font-bold uppercase text-[9px]">-</span></div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block mb-1 text-[10px] uppercase font-bold">Ke Pejabat (Penerima)</span>
                            <div id="modal-ke-nama" class="font-bold text-slate-900 dark:text-white">-</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">NIP: <span id="modal-ke-nip" class="font-mono font-semibold">-</span></div>
                            <div class="mt-2"><span id="modal-ke-role" class="px-2 py-0.5 rounded bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 font-bold uppercase text-[9px]">-</span></div>
                        </div>
                    </div>

                    <!-- Paket info (jika ada) -->
                    <div id="modal-paket-section" class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Paket Terkait</span>
                        <div id="modal-paket-nama" class="font-bold text-slate-900 dark:text-white">-</div>
                        <div class="text-[10px] text-slate-450 dark:text-slate-500">Kode RUP: <span id="modal-paket-rup" class="font-mono font-bold">-</span></div>
                    </div>

                    <!-- Alasan Mutasi -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Alasan Pengaju</span>
                        <div id="modal-alasan" class="text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed font-semibold">-</div>
                    </div>

                    <!-- Admin Approval Detail (jika ada) -->
                    <div id="modal-admin-section" class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-[10px] uppercase font-bold">Diproses Oleh</span>
                            <span id="modal-admin-nama" class="font-bold text-slate-900 dark:text-white">-</span>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-850 pt-2">
                            <span class="text-slate-400 block mb-0.5 text-[10px] uppercase font-bold">Catatan Administrator</span>
                            <div id="modal-catatan-admin" class="text-slate-700 dark:text-slate-350 italic">-</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeTransferModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold transition text-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle & Modal Javascript -->
    <script>
        function toggleRejectForm(id) {
            const row = document.getElementById('reject-row-' + id);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

        function showTransferDetail(data) {
            document.getElementById('modal-dari-nama').innerText = data.dari_nama;
            document.getElementById('modal-dari-nip').innerText = data.dari_nip;
            document.getElementById('modal-dari-role').innerText = data.dari_role;
            
            document.getElementById('modal-ke-nama').innerText = data.ke_nama;
            document.getElementById('modal-ke-nip').innerText = data.ke_nip;
            document.getElementById('modal-ke-role').innerText = data.ke_role;
            
            document.getElementById('modal-tanggal').innerText = data.tanggal;
            document.getElementById('modal-alasan').innerText = data.alasan;
            
            // Status badge styling
            const statusEl = document.getElementById('modal-status');
            statusEl.innerText = data.status.toUpperCase();
            statusEl.className = 'px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-[10px] ';
            if (data.status === 'menunggu') {
                statusEl.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/40', 'dark:text-amber-300');
            } else if (data.status === 'disetujui') {
                statusEl.classList.add('bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/40', 'dark:text-emerald-300');
            } else {
                statusEl.classList.add('bg-rose-100', 'text-rose-800', 'dark:bg-rose-900/40', 'dark:text-rose-300');
            }
            
            // Paket info
            if (data.paket_nama) {
                document.getElementById('modal-paket-section').classList.remove('hidden');
                document.getElementById('modal-paket-nama').innerText = data.paket_nama;
                document.getElementById('modal-paket-rup').innerText = data.paket_rup;
            } else {
                document.getElementById('modal-paket-section').classList.add('hidden');
            }
            
            // Admin info
            if (data.admin_nama) {
                document.getElementById('modal-admin-section').classList.remove('hidden');
                document.getElementById('modal-admin-nama').innerText = data.admin_nama;
                document.getElementById('modal-catatan-admin').innerText = data.catatan_admin || '-';
            } else {
                document.getElementById('modal-admin-section').classList.add('hidden');
            }
            
            document.getElementById('detail-modal').classList.remove('hidden');
        }

        function closeTransferModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
