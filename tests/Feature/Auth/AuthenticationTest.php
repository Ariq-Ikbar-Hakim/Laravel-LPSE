<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this->post('/login', [
        'nip' => $user->nip,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $this->post('/login', [
        'nip' => $user->nip,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('pending users can not authenticate', function () {
    $user = User::factory()->create(['status_aktif' => 0]);

    $response = $this->post('/login', [
        'nip' => $user->nip,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('nip');
});

test('users can logout', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
