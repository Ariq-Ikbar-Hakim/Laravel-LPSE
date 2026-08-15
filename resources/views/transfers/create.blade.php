<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-955 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengajuan Swap Jabatan & Peran</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Ajukan pertukaran peran jabatan dan penyerahan seluruh paket tugas Anda ke pejabat lain.</p>
                </div>
            </div>

            <!-- Error/Success Alert -->
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 flex items-center gap-3 text-rose-800 dark:text-rose-355 text-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
                    <span><strong class="font-bold">Gagal!</strong> {{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-6">
                @if($pendingSwap)
                    <!-- Pending Swap Info -->
                    <div class="p-6 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 rounded-2xl text-center space-y-4">
                        <div class="w-16 h-16 bg-amber-100 dark:bg-amber-950 rounded-full flex items-center justify-center mx-auto text-amber-600 dark:text-amber-400">
                            <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm">Sudah melakukan transfer jabatan mohon menunggu persetujuan admin</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-normal max-w-md mx-auto">
                                Anda telah mengajukan swap jabatan/peran ke <span class="font-semibold text-slate-900 dark:text-white">{{ $pendingSwap->keUser->nama }}</span> pada tanggal {{ $pendingSwap->created_at->format('d M Y, H:i') }}.
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition cursor-pointer">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <!-- User Summary Box -->
                    <div class="p-5 bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/40 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm">
                        <div>
                            <h3 class="font-bold text-indigo-955 dark:text-indigo-400 text-xs uppercase tracking-wider mb-2">Profil Jabatan Anda</h3>
                            <div class="space-y-1 text-xs">
                                <div class="text-slate-600 dark:text-slate-400">
                                    Nama: <span class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->nama }}</span>
                                </div>
                                <div class="text-slate-600 dark:text-slate-400">
                                    Peran Aktif: 
                                    <span class="px-2.5 py-0.5 rounded bg-indigo-100 dark:bg-indigo-950/50 text-indigo-705 dark:text-indigo-400 font-bold text-[10px] uppercase ml-1">
                                        {{ Auth::user()->jabatan_aktif }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="sm:text-right">
                            <div class="text-xs text-slate-500 dark:text-slate-400">Total Paket Terikat:</div>
                            <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $packageCount }} Paket</div>
                        </div>
                    </div>

                    <!-- Swap Form -->
                    <form action="{{ route('transfers.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Target User Dropdown -->
                        <div>
                            <label for="ke_user_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Pilih Pejabat Penerima Swap</label>
                            <select name="ke_user_id" id="ke_user_id" required
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition duration-200">
                                <option value="" disabled selected>-- Pilih Pejabat PPK / PP Aktif --</option>
                                @foreach($users as $targetUser)
                                    <option value="{{ $targetUser->id }}">
                                        [{{ $targetUser->jabatan_aktif }}] {{ $targetUser->nama }} (NIP: {{ $targetUser->nip }} &bull; OPD: {{ $targetUser->opd ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('ke_user_id')" class="mt-2 text-xs text-rose-500" />
                            <p class="text-[10.5px] text-slate-400 dark:text-slate-500 mt-1.5 leading-normal">
                                Menampilkan seluruh pengguna berstatus aktif dengan wewenang <span class="font-bold text-slate-800 dark:text-slate-350">PPK</span> atau <span class="font-bold text-slate-800 dark:text-slate-355">PP</span> (kecuali diri Anda sendiri).
                            </p>
                        </div>

                        <!-- Alasan Swap -->
                        <div>
                            <label for="alasan" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Alasan Swap Jabatan / Mutasi</label>
                            <textarea name="alasan" id="alasan" rows="4" required
                                      placeholder="Jelaskan secara rinci alasan mutasi jabatan atau kendala berhalangan tugas Anda..." 
                                      class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition duration-200">{{ old('alasan') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan')" class="mt-2 text-xs text-rose-500" />
                        </div>

                        <!-- Warning Banner -->
                        <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40 text-amber-800 dark:text-amber-300 rounded-2xl text-xs leading-relaxed font-medium">
                            <strong>PENTING:</strong> Pengajuan ini merupakan permohonan swap peran/tugas penuh yang membutuhkan persetujuan Administrator LPSE. Setelah disetujui:
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Jika pejabat tujuan memiliki peran berbeda (PPK <-> PP), jabatan Anda berdua akan saling bertukar peran secara otomatis.</li>
                                <li>Jika pejabat tujuan memiliki peran sama (PPK <-> PPK / PP <-> PP), peran Anda tidak berubah.</li>
                                <li>Seluruh paket tugas Anda akan diserahterimakan kepada pejabat tujuan, dan sebaliknya (seluruh paket tugas pejabat tujuan dialihkan ke Anda).</li>
                            </ul>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition cursor-pointer">
                                Kirim Pengajuan Swap
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                Batal
                            </a>
                        </div>

                    </form>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>
