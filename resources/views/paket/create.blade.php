<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Usulan Paket Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('paket.store') }}" class="space-y-6">
                        @csrf

                        <!-- Kode RUP -->
                        <div>
                            <x-input-label for="kode_rup" :value="__('Kode RUP (Rencana Umum Pengadaan)')" />
                            <x-text-input id="kode_rup" class="block mt-1 w-full" type="text" name="kode_rup" :value="old('kode_rup')" required autofocus placeholder="Masukkan Kode RUP (misal: 489201)" />
                            <x-input-error :messages="$errors->get('kode_rup')" class="mt-2" />
                        </div>

                        <!-- Nama Paket -->
                        <div>
                            <x-input-label for="nama_paket" :value="__('Nama Paket Pengadaan')" />
                            <x-text-input id="nama_paket" class="block mt-1 w-full" type="text" name="nama_paket" :value="old('nama_paket')" required placeholder="Masukkan Nama Paket Pengadaan" />
                            <x-input-error :messages="$errors->get('nama_paket')" class="mt-2" />
                        </div>

                        <!-- Pagu Anggaran -->
                        <div>
                            <x-input-label for="pagu" :value="__('Pagu Anggaran (Rupiah)')" />
                            <x-text-input id="pagu" class="block mt-1 w-full" type="number" name="pagu" step="0.01" min="0" :value="old('pagu')" required placeholder="Masukkan Pagu Dana (misal: 150000000)" />
                            <x-input-error :messages="$errors->get('pagu')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('paket.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-150 underline mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                Simpan Draft Paket
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
