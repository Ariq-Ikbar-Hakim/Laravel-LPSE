<?php

use App\Models\BeritaAcara;
use App\Models\Paket;
use App\Models\User;

test('all navigation pages render for their assigned role', function (string $role, array $pages) {
    $user = User::factory()->create(['jabatan_aktif' => $role, 'status_aktif' => 1]);
    $ppk = $role === 'PPK' ? $user : User::factory()->create(['jabatan_aktif' => 'PPK']);
    $pp = $role === 'PP' ? $user : User::factory()->create(['jabatan_aktif' => 'PP']);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'pp_id' => $pp->id, 'status' => 'dikirim']);
    BeritaAcara::create([
        'paket_id' => $paket->id, 'nomor_ba' => 'BA/SMOKE/1',
        'tanggal_ba' => now(), 'verification_hash' => 'smoke-hash', 'status' => 'draft',
    ]);
    $this->actingAs($user);
    foreach ($pages as $page) {
        $this->get($page)->assertOk();
    }
    $this->get(route('paket.show', $paket))->assertOk();
})->with([
    'admin' => ['admin', ['/dashboard', '/profile', '/request-reset', '/berita-acara', '/admin/users', '/admin/users/verification', '/admin/users/reset-password', '/admin/paket', '/admin/transfers']],
    'PPK' => ['PPK', ['/dashboard', '/profile', '/request-reset', '/berita-acara', '/paket', '/paket/create', '/transfers/create']],
    'PP' => ['PP', ['/dashboard', '/profile', '/request-reset', '/berita-acara', '/paket-review', '/paket-bypass/create', '/transfers/create']],
]);

test('public magic login cannot authenticate or reset an admin', function () {
    $admin = User::factory()->create(['nip' => '1234567890123456', 'jabatan_aktif' => 'admin']);
    $passwordHash = $admin->password;
    $this->get('/magic-login')->assertNotFound();
    $this->assertGuest();
    expect($admin->fresh()->password)->toBe($passwordHash);
});
