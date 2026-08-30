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
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dokumen SIRUP secara otomatis akan diekstrak menjadi data paket.</p>
                </div>
            </div>

            <!-- Card Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm">
                
                <!-- Header Card -->
                <div class="flex items-start gap-4 mb-8">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-pdf text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Upload Dokumen SIRUP</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Unggah dokumen PDF SIRUP atau Detail Paket untuk mengisi data paket secara otomatis.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('paket.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- File PDF -->
                    <div class="space-y-1.5">
                        <label for="pdf_sirup" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            File PDF SIRUP <span class="text-red-500">*</span>
                        </label>
                        <input id="pdf_sirup" 
                               type="file" 
                               name="pdf_sirup" 
                               accept=".pdf"
                               required 
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:border-indigo-500 transition text-slate-900 dark:text-white" />
                        <x-input-error :messages="$errors->get('pdf_sirup')" class="mt-2 text-xs text-rose-500" />
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

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100 dark:border-slate-800 mt-8">
                        <a href="{{ route('paket.index') }}" class="text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold bg-blue-600 dark:bg-blue-600 text-white hover:bg-blue-700 transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                            Upload & Simpan Draft <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
