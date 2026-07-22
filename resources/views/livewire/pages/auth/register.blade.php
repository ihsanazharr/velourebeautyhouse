<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('customer');

            Customer::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'is_active' => true,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(
            session()->has('intended_booking')
                ? route('customer.booking.create', absolute: false)
                : route('customer.dashboard', absolute: false),
            navigate: true
        );
    }
}; ?>

<div>
    <style>
        .veloure-register :where(input[type="email"], input[type="password"], input[type="text"], input[type="tel"]) {
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
        .veloure-register :where(input[type="email"], input[type="password"], input[type="text"], input[type="tel"]):focus {
            outline: none !important;
            border-color: #B89B6B !important;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12) !important;
            background: white !important;
        }
    </style>

    <div class="veloure-register">
        <div class="text-center mb-8">
            <h2 class="font-display text-2xl text-[#1C1A18] font-semibold mb-2">Pendaftaran Akun</h2>
            <p class="font-body text-xs" style="color: #9C9690;">
                Lengkapi form di bawah untuk membuat akun customer baru Anda.
            </p>
        </div>

        <form wire:submit="register" class="space-y-5">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />
                <x-text-input wire:model="name" id="name" class="block mt-2 w-full" type="text" name="name" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />
                <x-text-input wire:model="email" id="email" class="block mt-2 w-full" type="email" name="email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Nomor WhatsApp')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />
                <x-text-input wire:model="phone" id="phone" class="block mt-2 w-full" type="tel" name="phone" required autocomplete="tel" placeholder="08xxxxxxxxxx" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <x-input-label for="password" :value="__('Password')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />

                <div class="relative mt-2" wire:ignore>
                    <x-text-input 
                        wire:model="password" 
                        id="password" 
                        class="block w-full pr-10"
                        x-bind:type="show ? 'text' : 'password'"
                        name="password"
                        required 
                        autocomplete="new-password" 
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

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-body text-xs tracking-[0.1em] uppercase" style="color: #3A3733;" />

                <div class="relative mt-2" wire:ignore>
                    <x-text-input 
                        wire:model="password_confirmation" 
                        id="password_confirmation" 
                        class="block w-full pr-10"
                        x-bind:type="show ? 'text' : 'password'"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
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

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center font-body text-xs tracking-[0.15em] uppercase py-4 transition-all duration-300 hover:opacity-90 focus:outline-none" style="background-color: #1C1A18; color: #FAF9F7; border-radius: 8px;">
                    Daftar Sekarang
                </button>
            </div>

            <div class="pt-6 border-t text-center" style="border-color: #EDEAE4; margin-top: 24px;">
                <p class="font-body text-xs mb-3" style="color: #9C9690;">
                    Sudah memiliki akun customer?
                </p>
                <a href="{{ route('login') }}" wire:navigate class="w-full inline-flex items-center justify-center font-body text-xs tracking-[0.15em] uppercase py-3 border transition-colors duration-300 hover:border-[#B89B6B]" style="border-color: #EDEAE4; color: #1C1A18; border-radius: 8px;">
                    Masuk ke Akun Anda
                </a>
            </div>
        </form>
    </div>
</div>
