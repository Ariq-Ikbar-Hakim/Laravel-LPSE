<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Account</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola informasi profil dan detail akun pengadaan Anda.</p>
            </div>

            <!-- Form: Update Profile Info -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

        </div>
    </div>
</x-app-layout>
