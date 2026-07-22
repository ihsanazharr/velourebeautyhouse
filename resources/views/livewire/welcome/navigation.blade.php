<nav class="flex items-center gap-3">
    @auth
        @php
            $dashboardUrl = url('/dashboard');
            if (auth()->user()->hasRole('owner')) {
                $dashboardUrl = route('owner.dashboard');
            } elseif (auth()->user()->hasRole('kasir')) {
                $dashboardUrl = route('kasir.dashboard');
            } elseif (auth()->user()->hasRole('customer')) {
                $dashboardUrl = route('customer.dashboard');
            }
        @endphp
        <a
            href="{{ $dashboardUrl }}"
            wire:navigate
            class="font-body text-xs tracking-[0.12em] uppercase transition-all duration-200"
            style="background: #1C1A18; color: #FAF9F7; padding: 10px 20px; border-radius: 6px; text-decoration: none;"
            onmouseover="this.style.background='#3A3733'"
            onmouseout="this.style.background='#1C1A18'"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            wire:navigate
            class="font-body text-xs tracking-[0.12em] uppercase transition-all duration-200"
            style="color: #3A3733; padding: 10px 16px; text-decoration: none; font-weight: 500;"
            onmouseover="this.style.color='#B89B6B'"
            onmouseout="this.style.color='#3A3733'"
        >
            Masuk
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                wire:navigate
                class="font-body text-xs tracking-[0.12em] uppercase transition-all duration-200"
                style="background: #1C1A18; color: #FAF9F7; padding: 10px 20px; border-radius: 6px; text-decoration: none;"
                onmouseover="this.style.background='#3A3733'"
                onmouseout="this.style.background='#1C1A18'"
            >
                Daftar
            </a>
        @endif
    @endauth
</nav>
