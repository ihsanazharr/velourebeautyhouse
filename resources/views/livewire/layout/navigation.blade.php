<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav
    x-data="{
        open: false,
        activeDropdown: null,
        toggleDropdown(name) {
            this.activeDropdown = this.activeDropdown === name ? null : name;
        },
        closeAll() { this.activeDropdown = null; }
    }"
    @click.away="closeAll()"
    class="bg-white border-b border-[#EDEAE4] relative z-50"
    style="box-shadow: 0 1px 0 #EDEAE4;"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-[68px]">

            {{-- Logo --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center me-10">
                    <a
                        href="{{
                            auth()->user()->hasRole('owner')
                                ? route('owner.dashboard')
                                : (auth()->user()->hasRole('customer')
                                    ? route('customer.dashboard')
                                    : route('kasir.dashboard'))
                        }}"
                        wire:navigate
                        class="flex flex-col leading-none group"
                    >
                        <span class="font-display text-[22px] tracking-[0.12em] text-[#1C1A18] group-hover:text-[#B89B6B] transition-colors duration-300">
                            VELOURÉ
                        </span>
                        <span class="font-body text-[8px] tracking-[0.35em] text-[#9C9690] mt-0.5 uppercase">
                            Beauty House
                        </span>
                    </a>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden sm:flex sm:items-center h-full gap-0.5">

                    {{-- ==================== OWNER NAV ==================== --}}
                    @role('owner')

                    {{-- Dashboard --}}
                    <a
                        href="{{ route('owner.dashboard') }}"
                        wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}"
                    >
                        Dashboard
                    </a>

                    {{-- Dropdown: SDM --}}
                    <div class="relative h-full flex items-center">
                        <button
                            @click="toggleDropdown('sdm')"
                            class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center gap-1.5 border-b-2 transition-all duration-200 {{ request()->routeIs('owner.employees.*', 'owner.payrolls.*', 'owner.attendances.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}"
                        >
                            SDM
                            <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeDropdown==='sdm' ? 'rotate-180' : ''" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div
                            x-show="activeDropdown === 'sdm'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute top-full left-0 w-48 bg-white py-2"
                            style="border: 1px solid #EDEAE4; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); margin-top: 1px;"
                        >
                            <a href="{{ route('owner.employees.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.employees.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Karyawan
                            </a>
                            <a href="{{ route('owner.payrolls.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.payrolls.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Penggajian
                            </a>
                            <a href="{{ route('owner.attendances.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.attendances.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Absensi
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown: Operasional --}}
                    <div class="relative h-full flex items-center">
                        <button
                            @click="toggleDropdown('ops')"
                            class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center gap-1.5 border-b-2 transition-all duration-200 {{ request()->routeIs('owner.bookings.*', 'owner.services.*', 'owner.customers.*', 'owner.products.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}"
                        >
                            Operasional
                            <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeDropdown==='ops' ? 'rotate-180' : ''" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div
                            x-show="activeDropdown === 'ops'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute top-full left-0 w-48 bg-white py-2"
                            style="border: 1px solid #EDEAE4; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); margin-top: 1px;"
                        >
                            <a href="{{ route('owner.bookings.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.bookings.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Booking
                            </a>
                            <a href="{{ route('owner.customers.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.customers.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Customers
                            </a>
                            <a href="{{ route('owner.services.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.services.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Layanan
                            </a>
                            <a href="{{ route('owner.products.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.products.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Produk
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown: Keuangan --}}
                    <div class="relative h-full flex items-center">
                        <button
                            @click="toggleDropdown('fin')"
                            class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center gap-1.5 border-b-2 transition-all duration-200 {{ request()->routeIs('owner.invoices.*', 'owner.payment-proofs.*', 'owner.reports.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}"
                        >
                            Keuangan
                            <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeDropdown==='fin' ? 'rotate-180' : ''" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div
                            x-show="activeDropdown === 'fin'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute top-full left-0 w-48 bg-white py-2"
                            style="border: 1px solid #EDEAE4; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); margin-top: 1px;"
                        >
                            <a href="{{ route('owner.invoices.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.invoices.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Invoice
                            </a>
                            <a href="{{ route('owner.payment-proofs.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.payment-proofs.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Pembayaran
                            </a>
                            <div style="border-top: 1px solid #EDEAE4; margin: 6px 0;"></div>
                            <a href="{{ route('owner.reports.index') }}" wire:navigate @click="closeAll()" class="dropdown-item block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase {{ request()->routeIs('owner.reports.*') ? 'text-[#B89B6B]' : 'text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7]' }} transition-colors duration-150">
                                Laporan
                            </a>
                        </div>
                    </div>

                    @endrole

                    {{-- ==================== KASIR NAV ==================== --}}
                    @role('kasir')

                    <a href="{{ route('kasir.dashboard') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.dashboard') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('kasir.bookings.index') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.bookings.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Booking
                    </a>

                    <a href="{{ route('kasir.customers.index') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.customers.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Customers
                    </a>

                    <a href="{{ route('kasir.attendances.index') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.attendances.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Absensi
                    </a>

                    <a href="{{ route('kasir.invoices.index') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.invoices.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Invoice
                    </a>

                    <a href="{{ route('kasir.payment-proofs.index') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('kasir.payment-proofs.*') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Pembayaran
                    </a>

                    @endrole

                    {{-- ==================== CUSTOMER NAV ==================== --}}
                    @role('customer')
                    <a href="{{ route('customer.dashboard') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('customer.dashboard') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('customer.booking.create') }}" wire:navigate
                        class="nav-item font-body text-xs tracking-[0.12em] uppercase px-3 h-full flex items-center border-b-2 transition-all duration-200 {{ request()->routeIs('customer.booking.create') ? 'border-[#B89B6B] text-[#1C1A18]' : 'border-transparent text-[#9C9690] hover:text-[#1C1A18]' }}">
                        Booking
                    </a>
                    @endrole

                </div>
            </div>

            {{-- User Dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data>
                    <button
                        @click="$dispatch('toggle-user-menu')"
                        x-on:toggle-user-menu.window="activeDropdown = activeDropdown === 'user' ? null : 'user'"
                        class="inline-flex items-center gap-2.5 px-4 py-2 font-body text-xs transition-all duration-200 group"
                        style="border: 1px solid #EDEAE4; border-radius: 8px; color: #3A3733;"
                        onmouseover="this.style.borderColor='#B89B6B'; this.style.color='#1C1A18';"
                        onmouseout="this.style.borderColor='#EDEAE4'; this.style.color='#3A3733';"
                    >
                        {{-- Avatar initial --}}
                        <span
                            class="w-6 h-6 flex items-center justify-center font-display text-xs"
                            style="background: #F3EBE0; color: #B89B6B; border-radius: 50%;"
                        >
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <span class="font-body text-xs tracking-[0.05em]">{{ auth()->user()->name }}</span>

                        <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeDropdown==='user' ? 'rotate-180' : ''" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>

                    <div
                        x-show="activeDropdown === 'user'"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 top-full w-52 bg-white py-2"
                        style="margin-top: 6px; border: 1px solid #EDEAE4; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08);"
                    >
                        <div class="px-4 py-2 mb-1" style="border-bottom: 1px solid #EDEAE4;">
                            <p class="font-body text-xs font-medium" style="color: #1C1A18;">{{ auth()->user()->name }}</p>
                            <p class="font-body text-[10px] mt-0.5 tracking-[0.06em] uppercase" style="color: #B89B6B;">{{ auth()->user()->getRoleNames()->first() }}</p>
                        </div>
                        <a href="{{ route('profile') }}" wire:navigate @click="closeAll()"
                            class="block px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase text-[#3A3733] hover:text-[#1C1A18] hover:bg-[#FAF9F7] transition-colors duration-150">
                            Profil Saya
                        </a>
                        <button wire:click="logout" class="w-full text-left px-4 py-2.5 font-body text-xs tracking-[0.08em] uppercase text-[#B5402C] hover:bg-[#FEF2F0] transition-colors duration-150">
                            Keluar
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = !open"
                    class="inline-flex items-center justify-center p-2 text-[#9C9690] hover:text-[#B89B6B] hover:bg-[#FAF9F7] focus:outline-none transition duration-200"
                >
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-[#EDEAE4] bg-white">
        <div class="pt-2 pb-3 space-y-0.5 px-4">
            @role('owner')
            <p class="font-body text-[10px] tracking-[0.2em] uppercase px-3 py-2 mt-2" style="color:#B89B6B;">SDM</p>
            <x-responsive-nav-link :href="route('owner.dashboard')" wire:navigate>Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.employees.index')" wire:navigate>Karyawan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.payrolls.index')" wire:navigate>Penggajian</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.attendances.index')" wire:navigate>Absensi</x-responsive-nav-link>
            <p class="font-body text-[10px] tracking-[0.2em] uppercase px-3 py-2 mt-2" style="color:#B89B6B;">Operasional</p>
            <x-responsive-nav-link :href="route('owner.bookings.index')" wire:navigate>Booking</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.customers.index')" wire:navigate>Customers</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.services.index')" wire:navigate>Layanan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.products.index')" wire:navigate>Produk</x-responsive-nav-link>
            <p class="font-body text-[10px] tracking-[0.2em] uppercase px-3 py-2 mt-2" style="color:#B89B6B;">Keuangan</p>
            <x-responsive-nav-link :href="route('owner.invoices.index')" wire:navigate>Invoice</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.payment-proofs.index')" wire:navigate>Pembayaran</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('owner.reports.index')" wire:navigate>Laporan</x-responsive-nav-link>
            @endrole

            @role('kasir')
            <x-responsive-nav-link :href="route('kasir.dashboard')" wire:navigate>Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.bookings.index')" wire:navigate>Booking</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.customers.index')" wire:navigate>Customers</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.attendances.index')" wire:navigate>Absensi</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.invoices.index')" wire:navigate>Invoice</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kasir.payment-proofs.index')" wire:navigate>Pembayaran</x-responsive-nav-link>
            @endrole

            @role('customer')
            <x-responsive-nav-link :href="route('customer.dashboard')" wire:navigate>Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.booking.create')" wire:navigate>Booking</x-responsive-nav-link>
            @endrole
        </div>

        {{-- Mobile User Info --}}
        <div class="pt-4 pb-1 border-t border-[#EDEAE4]">
            <div class="px-6 mb-3">
                <div class="font-body font-medium text-sm text-[#1C1A18]"
                    x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                    x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name">
                </div>
                <div class="font-body text-xs text-[#9C9690] mt-0.5">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-2 space-y-0.5 px-4">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>Profil Saya</x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>Keluar</x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
