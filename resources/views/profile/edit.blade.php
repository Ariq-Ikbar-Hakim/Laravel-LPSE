<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Back & Header -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm" title="Kembali ke Dashboard">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Profil & Pengaturan Akun</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola informasi pribadi, ubah kata sandi, dan atur keamanan akun Anda.</p>
                </div>
            </div>

            <!-- Form 1: Update Profile Info -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Form 2: Update Password -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Form 3: Delete Account -->
            <div class="bg-white dark:bg-slate-900 border border-rose-200 dark:border-rose-950 rounded-3xl p-6 md:p-8 shadow-sm bg-rose-50/10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
