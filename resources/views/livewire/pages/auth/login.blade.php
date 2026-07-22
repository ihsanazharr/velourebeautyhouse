<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        if ($user->hasRole('owner')) {

            $this->redirect(
                route(
                    'owner.dashboard',
                    absolute: false
                ),
                navigate: true
            );

            return;
        }

        if ($user->hasRole('kasir')) {

            $this->redirect(
                route(
                    'kasir.dashboard',
                    absolute: false
                ),
                navigate: true
            );

            return;
        }

        if ($user->hasRole('customer')) {
            if (session()->has('intended_booking')) {
                $this->redirect(
                    route(
                        'customer.booking.create',
                        absolute: false
                    ),
                    navigate: true
                );

                return;
            }

            $this->redirect(
                route(
                    'customer.dashboard',
                    absolute: false
                ),
                navigate: true
            );

            return;
        }

        $this->redirect(
            route(
                'dashboard',
                absolute: false
            ),
            navigate: true
        );
    }
}; ?>

<div>
    <style>
        .veloure-login :where(input[type="email"], input[type="password"], input[type="text"]) {
            border-radius: 8px !important;
            border: 1px solid #EDEAE4 !important;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px !important;
            color: #1C1A18;
            background: #FAF9F7 !important;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .veloure-login :where(input[type="email"], input[type="password"], input[type="text"]):focus {
            outline: none !important;
            border-color: #B89B6B !important;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12) !important;
            background: white !important;
        }
    </style>

    <div class="veloure-login">

        <div class="text-center mb-8">
            <h2 class="font-display text-2xl text-[#1C1A18] font-semibold mb-2">Selamat Datang Kembali</h2>
            <p class="font-body text-xs" style="color: #9C9690;">
                Silakan masuk untuk mengelola pesanan atau menjadwalkan perawatan baru Anda.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-5">
            <!-- Email Address / Phone -->
            <div>
                <x-input-label for="email" :value="__('Email atau Nomor WhatsApp')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />
                <x-text-input wire:model="form.email" id="email" class="block mt-2 w-full" type="text" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <x-input-label for="password" :value="__('Password')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />

                <div class="relative mt-2" wire:ignore>
                    <x-text-input 
                        wire:model="form.password" 
                        id="password" 
                        class="block w-full pr-10"
                        x-bind:type="show ? 'text' : 'password'"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />
                    <button 
                        type="button" 
                        @click.prevent="show = !show" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    >
                        {{-- Eye icon when hidden --}}
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{-- Eye-off icon when shown --}}
                        <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#EDEAE4] text-[#B89B6B] shadow-sm focus:ring-[#B89B6B]" style="width: 1rem; height: 1rem;" name="remember">
                    <span class="ms-2 font-body text-xs" style="color: #9C9690;">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="font-body text-xs tracking-[0.05em] underline underline-offset-4" style="color: #9C9690;" href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center font-body text-xs tracking-[0.15em] uppercase py-4 transition-all duration-300 hover:opacity-90 focus:outline-none" style="background-color: #1C1A18; color: #FAF9F7; border-radius: 8px;">
                    Masuk
                </button>
            </div>

            @if (Route::has('register'))
                <div class="pt-6 border-t text-center" style="border-color: #EDEAE4; margin-top: 24px;">
                    <p class="font-body text-xs mb-3" style="color: #9C9690;">
                        Belum memiliki akun customer?
                    </p>
                    <a href="{{ route('register') }}" wire:navigate class="w-full inline-flex items-center justify-center font-body text-xs tracking-[0.15em] uppercase py-3 border transition-colors duration-300 hover:border-[#B89B6B]" style="border-color: #EDEAE4; color: #1C1A18; border-radius: 8px;">
                        Daftar Customer Baru
                    </a>
                </div>
            @endif
        </form>

    </div>
</div>
