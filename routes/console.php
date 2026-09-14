<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:reset-password {nip}', function () {
    $user = User::where('nip', $this->argument('nip'))
        ->where('jabatan_aktif', 'admin')->first();
    if (! $user) {
        $this->error('Akun admin dengan NIP tersebut tidak ditemukan.');

        return 1;
    }

    $password = $this->secret('Password baru (minimal 12 karakter)');
    if (! is_string($password) || strlen($password) < 12) {
        $this->error('Password harus berisi minimal 12 karakter.');

        return 1;
    }
    if ($password !== $this->secret('Ulangi password baru')) {
        $this->error('Konfirmasi password tidak cocok.');

        return 1;
    }

    $user->update(['password' => Hash::make($password)]);
    $this->info('Password admin diperbarui. Masuk melalui /login menggunakan NIP.');
})->purpose('Reset password admin melalui terminal, tanpa link login publik');
