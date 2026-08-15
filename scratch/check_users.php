<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::all();
foreach ($users as $u) {
    echo "ID: {$u->id}, NIP: {$u->nip}, Nama: {$u->nama}, Role: {$u->jabatan_aktif}, Status: {$u->status_aktif}, Foto Profil: " . ($u->foto_profil ?? 'NULL') . "\n";
}
