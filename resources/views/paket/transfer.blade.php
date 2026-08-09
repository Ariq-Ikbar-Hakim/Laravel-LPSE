<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajukan Mutasi / Transfer Kepemilikan Paket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-150 space-y-6">

                    <!-- Paket Summary -->
                    <div class="p-4 bg-indigo-50 dark:bg-gray-750 rounded-lg border border-indigo-150 dark:border-indigo-900 text-sm">
                        <h3 class="font-bold text-indigo-900 dark:text-indigo-200 text-xs uppercase tracking-wider mb-2">Paket yang akan ditransfer</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-gray-450 block">Nama Paket:</span>
                                <span class="font-semibold text-gray-850 dark:text-gray-200">{{ $paket->nama_paket }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block">Kode RUP:</span>
                                <span class="font-mono text-gray-800 dark:text-gray-300">{{ $paket->kode_rup }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block">Pagu Anggaran:</span>
                                <span class="font-bold text-indigo-700 dark:text-indigo-400">Rp {{ number_format($paket->pagu, 2, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block">Peran Aktif Pengaju:</span>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded font-semibold text-[10px]">
                                    {{ Auth::user()->jabatan_aktif }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Form -->
                    <form action="{{ route('paket.transfer.store', $paket) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Target User Dropdown -->
                        <div>
                            <x-input-label for="ke_user_id" :value="__('Pilih Pejabat Penerima Tugas')" class="text-xs font-semibold" />
                            <select name="ke_user_id" id="ke_user_id" class="mt-1 block w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled selected>-- Pilih Pejabat ({{ Auth::user()->jabatan_aktif }}) Aktif --</option>
                                @foreach($users as $targetUser)
                                    <option value="{{ $targetUser->id }}">
                                        {{ $targetUser->nama }} (NIP: {{ $targetUser->nip }} &bull; OPD: {{ $targetUser->opd ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('ke_user_id')" class="mt-1" />
                            <p class="text-[10.5px] text-gray-400 mt-1 leading-normal">
                                Hanya menampilkan pengguna aktif dengan role jabatan aktif yang sama ({{ Auth::user()->jabatan_aktif }}) untuk melanjutkan tugas Anda.
                            </p>
                        </div>

                        <!-- Alasan Transfer -->
                        <div>
                            <x-input-label for="alasan" :value="__('Alasan Mutasi / Transfer Tugas')" class="text-xs font-semibold" />
                            <textarea name="alasan" id="alasan" rows="4" class="mt-1 block w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tuliskan alasan pengalihan tugas ini secara detail..." required>{{ old('alasan') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan')" class="mt-1" />
                        </div>

                        <!-- Info Warn -->
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 rounded text-[11px] leading-normal">
                            <strong>PENTING:</strong> Pengajuan mutasi ini bersifat permanen dan memerlukan persetujuan dari Administrator LPSE. Setelah disetujui, kepemilikan paket akan berpindah sepenuhnya dan Anda tidak lagi memiliki hak akses manipulasi untuk paket ini.
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center space-x-3 pt-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-xs transition duration-150 uppercase tracking-widest">
                                Kirim Pengajuan
                            </button>
                            <a href="{{ route('paket.show', $paket) }}" class="text-xs text-gray-500 hover:underline">
                                Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
