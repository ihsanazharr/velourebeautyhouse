<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Portal Customer</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em] text-[#1C1A18]">
                Selamat datang, {{ auth()->user()->name }}!
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                Pantau status booking dan riwayat transaksi Anda.
            </p>
        </div>
        <a
            href="{{ route('customer.booking.create') }}"
            wire:navigate
            class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-200 inline-flex items-center gap-2"
            style="background: #1C1A18; color: #FAF9F7; padding: 13px 22px; text-decoration: none; border-radius: 8px;"
            onmouseover="this.style.background='#3A3733'"
            onmouseout="this.style.background='#1C1A18'"
        >
            + Booking Online
        </a>
    </div>

    {{-- Stats Cards Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Total Bookings --}}
        <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-body text-[10px] tracking-[0.15em] uppercase mb-1" style="color: #9C9690;">Total Booking</p>
                    <h3 class="font-display text-3xl font-semibold text-[#1C1A18]">{{ $stats['total'] }}</h3>
                </div>
                <div class="p-3 rounded-lg" style="background: #FAF9F7; border: 1px solid #EDEAE4;">
                    <svg class="w-5 h-5" style="color: #B89B6B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-body text-[10px] tracking-[0.15em] uppercase mb-1" style="color: #9C9690;">Selesai</p>
                    <h3 class="font-display text-3xl font-semibold" style="color: #5C7A5C;">{{ $stats['completed'] }}</h3>
                </div>
                <div class="p-3 rounded-lg" style="background: #FAF9F7; border: 1px solid #EDEAE4;">
                    <svg class="w-5 h-5" style="color: #5C7A5C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-body text-[10px] tracking-[0.15em] uppercase mb-1" style="color: #9C9690;">Menunggu</p>
                    <h3 class="font-display text-3xl font-semibold" style="color: #B89B6B;">{{ $stats['pending'] }}</h3>
                </div>
                <div class="p-3 rounded-lg" style="background: #FAF9F7; border: 1px solid #EDEAE4;">
                    <svg class="w-5 h-5" style="color: #B89B6B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section --}}
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Booking List (Left/Middle) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-1">
                    <span class="h-px w-6" style="background-color: #B89B6B;"></span>
                    <span class="font-body text-[10px] tracking-[0.2em] uppercase" style="color: #9C9690;">Daftar Kunjungan</span>
                </div>
                <h2 class="font-display text-2xl text-[#1C1A18]">Riwayat Booking Anda</h2>
            </div>

            @forelse($bookings as $booking)
                <div class="bg-white transition-all duration-200" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                        <div>
                            <span class="font-body text-xs tracking-[0.08em]" style="color: #9C9690;">{{ $booking->booking_number }}</span>
                            <h4 class="font-display text-lg font-semibold text-[#1C1A18] mt-1">
                                {{ $booking->booking_date->format('d M Y') }}
                            </h4>
                            @if($booking->booking_start)
                                <p class="font-body text-xs text-[#9C9690] mt-0.5">
                                    Pukul: {{ $booking->booking_start->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @php
                                $statusColors = [
                                    'completed' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C', 'label' => 'Selesai'],
                                    'confirmed' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C', 'label' => 'Dikonfirmasi'],
                                    'pending'   => ['bg' => '#FBF3E8', 'text' => '#B89B6B', 'label' => 'Menunggu'],
                                    'cancelled' => ['bg' => '#FBEAE7', 'text' => '#B5402C', 'label' => 'Dibatalkan'],
                                ];
                                $statusKey = strtolower($booking->status);
                                $style = $statusColors[$statusKey] ?? ['bg' => '#EDEAE4', 'text' => '#3A3733', 'label' => $booking->status];
                            @endphp
                            <span class="font-body text-[10px] tracking-[0.1em] uppercase px-3 py-1.5 inline-block" style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; border-radius: 4px;">
                                {{ $style['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Services list --}}
                    <div style="border-top: 1px dashed #EDEAE4; border-bottom: 1px dashed #EDEAE4;" class="py-3 my-4 space-y-2">
                        @foreach($booking->services as $bs)
                            <div class="flex justify-between items-center text-sm font-body">
                                <span class="text-[#3A3733]">{{ $bs->service?->name }}</span>
                                <span class="text-[#1C1A18] font-semibold">Rp {{ number_format($bs->price) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Invoice --}}
                    <div class="flex justify-between items-center flex-wrap gap-3">
                        <div>
                            <span class="font-body text-[10px] uppercase tracking-[0.1em]" style="color: #9C9690;">Total Pembayaran</span>
                            <p class="font-body text-base font-semibold text-[#1C1A18] mt-0.5">Rp {{ number_format($booking->total_amount) }}</p>
                        </div>
                        @if($booking->invoice)
                            <a
                                href="{{ route('customer.invoices.show', $booking->invoice->id) }}"
                                class="font-body text-xs tracking-[0.08em] uppercase underline underline-offset-4"
                                style="color: #B89B6B; text-decoration-color: #EDEAE4;"
                                onmouseover="this.style.textDecorationColor='#B89B6B'"
                                onmouseout="this.style.textDecorationColor='#EDEAE4'"
                            >
                                Lihat Invoice Struk
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white text-center font-body text-sm" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 56px 24px; color: #9C9690;">
                    <div style="font-size: 32px; opacity: 0.5; margin-bottom: 12px;">📅</div>
                    Belum ada riwayat booking. Silakan lakukan booking online.
                </div>
            @endforelse

            <div class="mt-6 font-body text-sm">
                {{ $bookings->links() }}
            </div>
        </div>

        {{-- Sidebar (Right) --}}
        <div class="space-y-6">
            {{-- Service hours & Contact --}}
            <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                <h3 class="font-display text-lg text-[#1C1A18] mb-4">Jam Operasional</h3>
                <div class="space-y-3 font-body text-sm text-[#3A3733]">
                    <div class="flex justify-between">
                        <span style="color: #9C9690;">Senin — Minggu</span>
                        <span>11:00 - 20:00 WIB</span>
                    </div>
                    <div style="border-top: 1px solid #EDEAE4;" class="pt-4 mt-4">
                        <p class="text-xs uppercase tracking-[0.1em] mb-1" style="color: #9C9690;">Butuh bantuan?</p>
                        <p class="font-semibold text-[#1C1A18] mb-3">Kontak Veloure WhatsApp</p>
                        <a
                            href="https://wa.me/6288218898999"
                            target="_blank"
                            class="w-full inline-flex justify-center items-center gap-2 font-body text-xs tracking-[0.1em] uppercase py-3 transition-all duration-200"
                            style="background: #5C7A5C; color: white; border-radius: 8px; text-decoration: none;"
                            onmouseover="this.style.opacity='0.88'"
                            onmouseout="this.style.opacity='1'"
                        >
                            💬 Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                <h3 class="font-display text-lg text-[#1C1A18] mb-2">Lokasi Kami</h3>
                <p class="font-body text-xs leading-relaxed" style="color: #9C9690;">
                    Jl. Sukamekar III No.2, Sukawarna, Kec. Sukajadi, Kota Bandung, Jawa Barat 40164
                </p>
            </div>
        </div>
    </div>

</div>
