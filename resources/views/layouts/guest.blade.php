<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BANGEDI LPSE') }}</title>

        <!-- Fonts & FontAwesome -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #f1f5f9; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="bg-gradient-to-tr from-indigo-600 via-indigo-700 to-violet-850 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

        <!-- Background blur vectors -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-400/20 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-5xl bg-white rounded-[32px] shadow-2xl shadow-indigo-950/30 overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 border border-white/20">
            
            <!-- Left Banner Section -->
            <div class="lg:col-span-5 bg-gradient-to-b from-[#f6f6f6] to-[#e4e4e6] p-8 lg:p-10 flex flex-col justify-between text-slate-800 relative overflow-hidden min-h-[350px] lg:min-h-[600px] border-r border-slate-100">
                <!-- Brand Logo -->
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-10 h-10 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('assets/logo-dpmd-bangkalan.png') }}" alt="Logo DPMD" class="w-7 h-7 object-contain">
                    </div>
                    <span class="font-extrabold text-base tracking-wide uppercase font-jakarta text-indigo-900">BANGEDI LPSE</span>
                </div>

                <!-- Content Center -->
                <div class="my-6 lg:my-10 relative z-10 space-y-3">
                    <h1 class="text-2xl lg:text-3xl font-extrabold leading-tight text-slate-900">
                        Sistem Informasi Pengadaan Barang & Jasa
                    </h1>
                    <p class="text-slate-500 text-xs leading-relaxed font-semibold">
                        Selamat datang kembali! Kelola berkas tender dan mutasi paket pengadaan DPMD Kabupaten Bangkalan secara efisien, transparan, dan terintegrasi.
                    </p>
                </div>

                <!-- Illustration Image -->
                <div class="relative z-10 flex justify-center mt-auto w-full">
                    <img src="{{ asset('assets/ilustrasi-login.png') }}" alt="Ilustrasi Login" class="max-h-56 lg:max-h-72 object-contain select-none">
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-white overflow-y-auto max-h-[90vh] lg:max-h-none">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
