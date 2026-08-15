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
        'no_telp' => '081234567890',
        'jabatan_aktif' => 'PPK',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('register.pending'));

    $this->assertDatabaseHas('users', [
        'nip' => '1234567890123456',
        'status_aktif' => 0,
    ]);
});
