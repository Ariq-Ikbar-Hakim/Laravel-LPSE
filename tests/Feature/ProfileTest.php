<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'opd' => 'Dinas Pendidikan',
            'no_telp' => '081234567890',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->nama);
    $this->assertSame('test@example.com', $user->email);
});

test('user can delete their account', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('user can upload profile photo', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    
    $user = User::factory()->create(['status_aktif' => 1]);
    $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg');

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'nama' => $user->nama,
            'email' => $user->email,
            'opd' => $user->opd ?? 'OPD Test',
            'no_telp' => $user->no_telp ?? '081234567890',
            'foto_profil' => $file,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/profile');

    $user->refresh();
    $this->assertNotNull($user->foto_profil);
    
    // Check file exists in fake storage
    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->foto_profil);
});
