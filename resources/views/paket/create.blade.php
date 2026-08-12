<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">

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
                
                <!-- Header Card -->
                <div class="flex items-start gap-4 mb-8">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Dasar Paket</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Isi detail paket pengadaan secara manual.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('paket.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode RUP -->
                        <div class="space-y-1.5">
                            <label for="kode_rup" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Kode RUP <span class="text-red-500">*</span>
                            </label>
                            <input id="kode_rup" 
                                   type="text" 
                                   name="kode_rup" 
                                   value="{{ old('kode_rup') }}" 
                                   required 
                                   autofocus 
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                            <x-input-error :messages="$errors->get('kode_rup')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Nama Paket -->
                        <div class="space-y-1.5">
                            <label for="nama_paket" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Nama Paket <span class="text-red-500">*</span>
                            </label>
                            <input id="nama_paket" 
                                   type="text" 
                                   name="nama_paket" 
                                   value="{{ old('nama_paket') }}" 
                                   required 
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                            <x-input-error :messages="$errors->get('nama_paket')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Pagu Anggaran -->
                        <div class="space-y-1.5">
                            <label for="pagu" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Pagu Anggaran (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input id="pagu" 
                                   type="number" 
                                   name="pagu" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('pagu') }}" 
                                   required 
                                   placeholder="Rp"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                            <x-input-error :messages="$errors->get('pagu')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Tahun Anggaran -->
                        <div class="space-y-1.5">
                            <label for="tahun_anggaran" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Tahun Anggaran <span class="text-red-500">*</span>
                            </label>
                            <input id="tahun_anggaran" 
                                   type="text" 
                                   name="tahun_anggaran" 
                                   value="{{ old('tahun_anggaran', date('Y')) }}" 
                                   required 
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                            <x-input-error :messages="$errors->get('tahun_anggaran')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Metode Pengadaan -->
                        <div class="space-y-1.5">
                            <label for="metode_pengadaan" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Metode Pengadaan
                            </label>
                            <input id="metode_pengadaan" 
                                   type="text" 
                                   name="metode_pengadaan" 
                                   value="{{ old('metode_pengadaan') }}" 
                                   placeholder="Contoh: E-Purchasing"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                            <x-input-error :messages="$errors->get('metode_pengadaan')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Sumber Dana -->
                        <div class="space-y-1.5">
                            <label for="sumber_dana" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Sumber Dana
                            </label>
                            <select id="sumber_dana" name="sumber_dana" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white">
                                <option value="">-- Pilih Sumber Dana --</option>
                                <option value="APBD" {{ old('sumber_dana') == 'APBD' ? 'selected' : '' }}>APBD</option>
                                <option value="APBN" {{ old('sumber_dana') == 'APBN' ? 'selected' : '' }}>APBN</option>
                                <option value="BLUD" {{ old('sumber_dana') == 'BLUD' ? 'selected' : '' }}>BLUD</option>
                            </select>
                            <x-input-error :messages="$errors->get('sumber_dana')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Jenis Pengadaan -->
                        <div class="space-y-1.5">
                            <label for="jenis_pengadaan" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jenis Pengadaan
                            </label>
                            <select id="jenis_pengadaan" name="jenis_pengadaan" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white">
                                <option value="">-- Pilih Jenis Pengadaan --</option>
                                <option value="Barang" {{ old('jenis_pengadaan') == 'Barang' ? 'selected' : '' }}>Barang</option>
                                <option value="Jasa Konsultansi" {{ old('jenis_pengadaan') == 'Jasa Konsultansi' ? 'selected' : '' }}>Jasa Konsultansi</option>
                                <option value="Pekerjaan Konstruksi" {{ old('jenis_pengadaan') == 'Pekerjaan Konstruksi' ? 'selected' : '' }}>Pekerjaan Konstruksi</option>
                                <option value="Jasa Lainnya" {{ old('jenis_pengadaan') == 'Jasa Lainnya' ? 'selected' : '' }}>Jasa Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_pengadaan')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Penugasan Pejabat Pengadaan (PP) -->
                        <div class="space-y-1.5">
                            <label for="pp_id" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Penugasan Pejabat Pengadaan (PP) <span class="text-red-500">*</span>
                            </label>
                            <select id="pp_id" name="pp_id" required class="w-full px-4 py-2.5 rounded-xl bg-blue-50/50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white">
                                <option value="">-- Pilih Pejabat Pengadaan --</option>
                                @foreach($ppUsers as $pp)
                                    <option value="{{ $pp->id }}" {{ old('pp_id') == $pp->id ? 'selected' : '' }}>{{ $pp->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pp_id')" class="mt-2 text-xs text-rose-500" />
                        </div>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div class="space-y-1.5 mt-6">
                        <label for="keterangan_tambahan" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Keterangan Tambahan
                        </label>
                        <textarea id="keterangan_tambahan" 
                                  name="keterangan_tambahan" 
                                  rows="3" 
                                  placeholder="Catatan operasional jika ada..."
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white resize-y">{{ old('keterangan_tambahan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan_tambahan')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100 dark:border-slate-800 mt-8">
                        <a href="{{ route('paket.index') }}" class="text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold bg-blue-600 dark:bg-blue-600 text-white hover:bg-blue-700 transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                            Simpan Draft & Lanjut Upload <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
