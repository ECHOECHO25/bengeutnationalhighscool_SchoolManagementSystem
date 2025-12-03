<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    public bool $isLockedOut = false;
    public int $remainingSeconds = 0;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            // Hit both email and password rate limiters
            RateLimiter::hit($this->emailThrottleKey());
            RateLimiter::hit($this->passwordThrottleKey());

            // Check which one triggered lockout
            $emailAttempts = RateLimiter::attempts($this->emailThrottleKey());
            $passwordAttempts = RateLimiter::attempts($this->passwordThrottleKey());

            $errors = [];

            if ($emailAttempts >= 5) {
                $errors['form.email'] = 'Too many failed attempts with this email address.';
            }

            if ($passwordAttempts >= 5) {
                $errors['form.password'] = 'Too many failed attempts with this password.';
            }

            // If no specific lockout yet, show generic error
            if (empty($errors)) {
                $errors['form.email'] = trans('auth.failed');
            }

            throw ValidationException::withMessages($errors);
        }

        // Clear both rate limiters on successful login
        RateLimiter::clear($this->emailThrottleKey());
        RateLimiter::clear($this->passwordThrottleKey());
        $this->clearLockoutTimer();
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        $emailLocked = RateLimiter::tooManyAttempts($this->emailThrottleKey(), 5);
        $passwordLocked = RateLimiter::tooManyAttempts($this->passwordThrottleKey(), 5);

        if (!$emailLocked && !$passwordLocked) {
            $this->isLockedOut = false;
            return;
        }

        event(new Lockout(request()));

        // Get the longer of the two lockout times
        $emailSeconds = $emailLocked ? RateLimiter::availableIn($this->emailThrottleKey()) : 0;
        $passwordSeconds = $passwordLocked ? RateLimiter::availableIn($this->passwordThrottleKey()) : 0;
        $seconds = max($emailSeconds, $passwordSeconds);

        $this->isLockedOut = true;
        $this->remainingSeconds = $seconds;

        // Store lockout time in cache for UI persistence
        Cache::put($this->lockoutCacheKey(), now()->addSeconds($seconds), $seconds);

        $errors = [];

        if ($emailLocked) {
            $errors['form.email'] = 'Too many attempts with this email. Please wait ' . ceil($seconds / 60) . ' minute(s).';
        }

        if ($passwordLocked) {
            $errors['form.password'] = 'Too many attempts with this password. Please wait ' . ceil($seconds / 60) . ' minute(s).';
        }

        throw ValidationException::withMessages($errors);
    }

    /**
     * Check if user is locked out and get remaining time
     */
    public function checkLockoutStatus(): void
    {
        $emailLocked = RateLimiter::tooManyAttempts($this->emailThrottleKey(), 5);
        $passwordLocked = RateLimiter::tooManyAttempts($this->passwordThrottleKey(), 5);

        if ($emailLocked || $passwordLocked) {
            $emailSeconds = $emailLocked ? RateLimiter::availableIn($this->emailThrottleKey()) : 0;
            $passwordSeconds = $passwordLocked ? RateLimiter::availableIn($this->passwordThrottleKey()) : 0;
            $seconds = max($emailSeconds, $passwordSeconds);

            $this->isLockedOut = true;
            $this->remainingSeconds = $seconds;
        } else {
            $this->isLockedOut = false;
            $this->remainingSeconds = 0;
        }
    }

    /**
     * Get remaining time in formatted string
     */
    public function getFormattedTime(): string
    {
        if (!$this->isLockedOut) {
            return '00:00';
        }

        $minutes = floor($this->remainingSeconds / 60);
        $seconds = $this->remainingSeconds % 60;

        return sprintf("%02d:%02d", $minutes, $seconds);
    }

    /**
     * Clear lockout timer
     */
    public function clearLockoutTimer(): void
    {
        Cache::forget($this->lockoutCacheKey());
        RateLimiter::clear($this->emailThrottleKey());
        RateLimiter::clear($this->passwordThrottleKey());
        $this->isLockedOut = false;
        $this->remainingSeconds = 0;
    }

    /**
     * Get the email-based rate limiting throttle key.
     */
    protected function emailThrottleKey(): string
    {
        return 'email:' . Str::transliterate(Str::lower($this->email)) . '|' . request()->ip();
    }

    /**
     * Get the password-based rate limiting throttle key.
     */
    protected function passwordThrottleKey(): string
    {
        return 'password:' . md5($this->password) . '|' . request()->ip();
    }

    /**
     * Get cache key for lockout timer
     */
    protected function lockoutCacheKey(): string
    {
        return 'login_lockout_' . md5(request()->ip());
    }
}
