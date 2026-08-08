<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('admin can generate reset password token and send email', function () {
    Mail::fake();

    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $user = User::factory()->create(['status_aktif' => 1]);

    $response = $this->actingAs($admin)
        ->post(route('admin.users.reset-token', $user));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Assert token exists in DB
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);

    // Assert email was sent
    Mail::assertSent(\App\Mail\ResetPasswordNotification::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('password can be reset with admin generated token', function () {
    $user = User::factory()->create(['status_aktif' => 1]);
    $token = bin2hex(random_bytes(32));

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        [
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]
    );

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('login'));

    // Check password updated
    $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
});
