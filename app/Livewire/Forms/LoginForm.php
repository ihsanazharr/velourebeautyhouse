<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginValue = trim($this->email);
        $credentials = ['password' => $this->password];

        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginValue;
        } else {
            // Treat as phone number. Look up customer or employee
            $userByPhone = null;

            // Search in Customer
            $customer = \App\Models\Customer::where('phone', $loginValue)->first();
            if ($customer && $customer->user_id) {
                $userByPhone = $customer->user;
            }

            // Search in Employee if not found in Customer
            if (!$userByPhone) {
                $employee = \App\Models\Employee::where('phone', $loginValue)->first();
                if ($employee && $employee->user_id) {
                    $userByPhone = $employee->user;
                }
            }

            if ($userByPhone) {
                $credentials['email'] = $userByPhone->email;
            } else {
                $credentials['email'] = 'nonexistent-phone-number-placeholder';
            }
        }

        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
