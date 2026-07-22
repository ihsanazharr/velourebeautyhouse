<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Veloure Beauty House</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="/favicon-veloure.jpg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #FAF9F7; color: #3A3733;">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
            
            {{-- Logo Header --}}
            <div class="mb-8">
                <a href="/" wire:navigate class="flex flex-col items-center leading-none group text-center">
                    <span class="font-display text-4xl tracking-[0.15em] text-[#1C1A18] group-hover:text-[#B89B6B] transition-colors duration-300">
                        VELOURÉ
                    </span>
                    <span class="font-body text-[9px] tracking-[0.4em] uppercase mt-1.5 text-[#9C9690]">
                        Beauty House
                    </span>
                </a>
            </div>

            {{-- Card Container --}}
            <div 
                class="w-full sm:max-w-md bg-white p-8 sm:p-10 transition-all duration-300"
                style="
                    border: 1px solid #EDEAE4; 
                    border-radius: 12px; 
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
                "
            >
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>
