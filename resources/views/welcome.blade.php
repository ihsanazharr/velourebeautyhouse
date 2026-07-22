<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Velour&eacute; Beauty House</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="/favicon-veloure.jpg">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --color-paper: #FAF9F7;
                --color-surface: #EDEAE4;
                --color-ash: #9C9690;
                --color-ink: #1C1A18;
                --color-ink-soft: #3A3733;
                --color-gold: #B89B6B;
            }
            .font-display { font-family: 'Cormorant Garamond', serif; }
            .font-body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="antialiased font-body" style="background-color: var(--color-paper); color: var(--color-ink-soft);">

        <div class="min-h-screen flex flex-col">

            <!-- Header -->
            <header class="px-6 lg:px-12 pt-6 pb-2 flex items-center justify-between max-w-7xl mx-auto w-full">
                <div class="flex flex-col leading-none">
                    <span class="font-display text-2xl tracking-[0.15em]" style="color: var(--color-ink);">VELOUR&Eacute;</span>
                    <span class="font-body text-[9px] tracking-[0.4em] uppercase mt-1" style="color: var(--color-ash);">Beauty House</span>
                </div>

                @if (Route::has('login'))
                    <livewire:welcome.navigation />
                @endif
            </header>

            <!-- Hero -->
            <main class="flex-1 flex flex-col">
                <div class="max-w-7xl mx-auto w-full px-6 lg:px-12 pt-4 pb-12 lg:pt-6 lg:pb-16">

                    <div class="grid lg:grid-cols-2 gap-16 items-center">

                        <div>
                            <div class="flex items-center gap-4 mb-8">
                                <span class="h-px w-10" style="background-color: var(--color-gold);"></span>
                                <span class="font-body text-xs tracking-[0.3em] uppercase" style="color: var(--color-gold);">Nails &middot; Hair &middot; Eyelash &middot; Academy</span>
                            </div>

                            <h1 class="font-display text-5xl lg:text-7xl leading-[1.05] mb-8" style="color: var(--color-ink);">
                                Kecantikan,<br>
                                dirawat dengan<br>
                                <span style="color: var(--color-gold);">presisi.</span>
                             </h1>

                            <p class="font-body text-sm sm:text-base leading-relaxed max-w-md mb-1" style="color: var(--color-ash);">
                                Selamat datang di Velouré Beauty House. Kami menghadirkan ruang perawatan eksklusif untuk kuku, rambut, dan bulu mata Anda dengan sentuhan terapis profesional dan teknik presisi tinggi.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="aspect-[4/3] w-full overflow-hidden border" style="border-color: var(--color-surface); border-radius: 12px; background-color: white;">
                                <img 
                                    src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80" 
                                    alt="Veloure Beauty House Interior" 
                                    class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                                >
                            </div>
                            <div class="absolute -bottom-6 -left-6 w-32 h-32 border hidden lg:block" style="border-color: var(--color-gold); border-radius: 12px; z-index: -1;"></div>
                        </div>

                    </div>

                    {{-- Daftar Layanan & Jadwal Booking Real-time (GMT+7) --}}
                    <livewire:booking.public-schedule />

                </div>
            </main>

            <!-- Footer -->
            <footer class="px-6 lg:px-12 py-10 max-w-7xl mx-auto w-full flex flex-col sm:flex-row items-center justify-between gap-4 border-t" style="border-color: var(--color-surface);">
                <div class="flex items-center gap-3">
                    <span class="font-display text-sm tracking-[0.15em]" style="color: var(--color-ink);">VELOUR&Eacute;</span>
                    <span class="h-1 w-1 rounded-full" style="background-color: var(--color-gold);"></span>
                    <span class="font-body text-xs" style="color: var(--color-ash);">Beauty House</span>
                </div>
                <p class="font-body text-xs italic" style="color: var(--color-ash);">
                    "Kecantikan yang dirawat dengan ketulusan dan presisi seni."
                </p>
            </footer>

        </div>

    </body>
</html>