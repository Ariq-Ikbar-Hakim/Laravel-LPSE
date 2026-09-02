<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['nullable', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Validate Captcha
        $captchaToken = $this->input('g-recaptcha-response');
        if (false) {
            if (!$this->validateRecaptcha($captchaToken)) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal.',
                ]);
            }
        }

        if (! Auth::attempt($this->only('nip', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'nip' => trans('auth.failed'),
            ]);
        }

        // Check status_aktif
        if (Auth::user()->status_aktif !== 1) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'nip' => 'Akun Anda masih ditinjau oleh Admin.',
            ]);
        }

        // Store jabatan_aktif in session for Force Logout check
        session(['user_jabatan' => Auth::user()->jabatan_aktif]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Validate reCAPTCHA token with Google API.
     */
    protected function validateRecaptcha(?string $token): bool
    {
        if (app()->environment('local', 'testing') && ($token === 'mock-captcha' || empty($token))) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret'),
                'response' => $token,
                'remoteip' => $this->ip(),
            ]);

            return $response->json('success') === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('nip')).'|'.$this->ip());
    }
}
