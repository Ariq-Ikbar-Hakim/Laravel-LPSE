<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- Back & Header -->
            <div class="flex items-center gap-3">
                <a href="{{ route('paket.index') }}" class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm" title="Kembali">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Buat Usulan Paket Baru</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftarkan usulan pengadaan barang/jasa baru Anda ke sistem.</p>
                </div>
            </div>

            <!-- Card Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm">
                <form method="POST" action="{{ route('paket.store') }}" class="space-y-6">
                    @csrf

                    <!-- Kode RUP -->
                    <div class="space-y-1.5">
                        <label for="kode_rup" class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            <i class="fa-solid fa-barcode mr-1.5 text-indigo-500"></i>Kode RUP (Rencana Umum Pengadaan)
                        </label>
                        <div class="relative">
                            <input id="kode_rup" 
                                   type="text" 
                                   name="kode_rup" 
                                   value="{{ old('kode_rup') }}" 
                                   required 
                                   autofocus 
                                   placeholder="Masukkan Kode RUP (misal: 489201)"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 text-slate-900 dark:text-white transition" />
                        </div>
                        <x-input-error :messages="$errors->get('kode_rup')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    <!-- Nama Paket -->
                    <div class="space-y-1.5">
                        <label for="nama_paket" class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            <i class="fa-solid fa-file-signature mr-1.5 text-indigo-500"></i>Nama Paket Pengadaan
                        </label>
                        <div class="relative">
                            <input id="nama_paket" 
                                   type="text" 
                                   name="nama_paket" 
                                   value="{{ old('nama_paket') }}" 
                                   required 
                                   placeholder="Masukkan Nama Paket Pengadaan"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 text-slate-900 dark:text-white transition" />
                        </div>
                        <x-input-error :messages="$errors->get('nama_paket')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    <!-- Pagu Anggaran -->
                    <div class="space-y-1.5">
                        <label for="pagu" class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            <i class="fa-solid fa-money-bill-wave mr-1.5 text-indigo-500"></i>Pagu Anggaran (Rupiah)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                                Rp
                            </span>
                            <input id="pagu" 
                                   type="number" 
                                   name="pagu" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('pagu') }}" 
                                   required 
                                   placeholder="Masukkan Pagu Dana (misal: 150000000)"
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 text-slate-900 dark:text-white transition" />
                        </div>
                        <x-input-error :messages="$errors->get('pagu')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('paket.index') }}" class="px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition border border-transparent">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900 dark:bg-slate-800 text-white hover:bg-indigo-600 transition shadow-md shadow-slate-900/10">
                            Simpan Draft Paket
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
