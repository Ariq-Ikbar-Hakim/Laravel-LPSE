<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register with pending status', function () {
    $response = $this->post('/register', [
        'nip' => '1234567890123456',
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'opd' => 'Dinas Pekerjaan Umum',
        'sub_unit_opd' => 'Bidang Bina Marga',
        'jabatan_aktif' => 'PPK',
        'sk_nomor' => 'SK/123/2026',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('register.pending'));

    $this->assertDatabaseHas('users', [
        'nip' => '1234567890123456',
        'status_aktif' => 0,
    ]);
});
