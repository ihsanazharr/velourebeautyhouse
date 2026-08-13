<div>

    @if (!$isBookingEnabled)
        {{-- Booking Online Tutup Notice --}}
        <section class="mt-16 bg-white border p-10 text-center max-w-2xl mx-auto" style="border-color: var(--color-surface); border-radius: 16px; box-shadow: 0 4px 20px rgba(28,26,24,0.03);">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6" style="background: #FBF3E8;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#B89B6B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            
            <h2 class="font-display text-2xl mb-4 text-[#1C1A18] font-semibold tracking-wide">
                Booking Online Sedang Ditutup
            </h2>
            
            <p class="font-body text-sm text-[#9C9690] leading-relaxed mb-8 max-w-md mx-auto">
                Mohon maaf, saat ini antrean di studio kami sedang ramai. Untuk kenyamanan dan pelayanan terbaik, pemesanan online dinonaktifkan sementara waktu.
            </p>
            
            <p class="font-body text-xs text-[#1C1A18] font-medium mb-4">
                Tetap ingin melakukan reservasi? Silakan hubungi kami via WhatsApp:
            </p>

            @php
                $cleanWa = preg_replace('/[^0-9]/', '', $whatsappNumber);
                if (str_starts_with($cleanWa, '0')) {
                    $cleanWa = '62' . substr($cleanWa, 1);
                }
            @endphp
            
            <a 
                href="https://wa.me/{{ $cleanWa }}?text=Halo%20Veloure%20Beauty%20House,%20saya%20ingin%20tanya%20jadwal%20booking%20yang%20tersedia" 
                target="_blank"
                class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-300 inline-flex items-center gap-2 hover:opacity-90"
                style="background: #25D366; color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none;"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="inline">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm5.835-4.26c1.654.982 3.28 1.487 4.908 1.488 5.48-.002 9.943-4.468 9.946-9.954.001-2.657-1.033-5.155-2.915-7.04C15.95 2.348 13.456 1.31 10.8 1.31 5.32 1.31.859 5.772.856 11.258c-.001 1.761.488 3.42 1.486 4.907L1.22 20.732l4.673-1.228zM18.23 15.25c-.29-.145-1.71-.845-1.975-.94-.266-.097-.46-.145-.653.145-.19.29-.74.94-.905 1.13-.167.19-.333.21-.623.065-2.453-1.224-4.015-2.422-5.466-4.92-.387-.668.388-.62 1.11-2.05.114-.228.058-.427-.03-.607-.087-.18-.74-1.79-.994-2.4-.25-.6-.524-.52-.716-.53-.186-.01-.398-.01-.61-.01s-.557.08-.85.4c-.29.32-1.11 1.085-1.11 2.65 0 1.563 1.137 3.076 1.296 3.29 1.59 2.185 3.06 4.3 6.365 5.564 2.56 1.026 3.33 1.06 4.5 1.06 1.17 0 2.69-.96 3.07-2.05.38-1.09.38-2.025.266-2.22-.115-.19-.387-.31-.678-.455z"/>
                </svg>
                Hubungi via WhatsApp
            </a>
        </section>
    @else
        {{-- Jadwal Hari Ini (Top Section) --}}
        <section class="mt-16">
            <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <span class="h-px w-10" style="background-color: var(--color-gold);"></span>
                    <span class="font-body text-xs tracking-[0.3em] uppercase" style="color: var(--color-gold);">Jadwal Hari Ini</span>
                </div>
                <p class="font-body text-xs" style="color: var(--color-ash);">
                    {{ $today->translatedFormat('l, d F Y') }} &middot; {{ $today->format('H:i') }} WIB
                </p>
            </div>

            @if ($slots->contains('available', true))
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                    @foreach ($slots as $slot)
                        @if ($slot['available'])
                            <button
                                type="button"
                                wire:click="selectSlot('{{ $slot['time'] }}')"
                                wire:loading.attr="disabled"
                                wire:key="slot-{{ $slot['time'] }}"
                                class="font-body text-xs py-3 border transition-colors duration-300 hover:bg-[var(--color-gold)] hover:text-white hover:border-[var(--color-gold)]"
                                style="border-color: var(--color-gold); color: var(--color-ink); background-color: white; border-radius: 6px;"
                            >
                                {{ $slot['time'] }}
                            </button>
                        @else
                            <span
                                class="font-body text-xs py-3 border text-center cursor-not-allowed"
                                style="border-color: var(--color-surface); color: var(--color-ash); background-color: var(--color-surface); border-radius: 6px;"
                            >
                                {{ $slot['time'] }}
                            </span>
                        @endif
                    @endforeach
                </div>

                <p class="font-body text-xs mt-6" style="color: var(--color-ash);">
                    Klik salah satu jam untuk melakukan booking. Anda perlu masuk (login) terlebih dahulu.
                </p>
            @else
                <div class="border p-8 text-center" style="border-color: var(--color-surface); border-radius: 12px;">
                    <p class="font-body text-sm" style="color: var(--color-ash);">
                        Tidak ada jadwal kosong tersisa untuk hari ini. Silakan cek kembali besok.
                    </p>
                </div>
            @endif
        </section>

        {{-- Daftar Layanan (Bottom Section) --}}
        <section class="mt-20">
            <div class="flex items-center gap-4 mb-8">
                <span class="h-px w-10" style="background-color: var(--color-gold);"></span>
                <span class="font-body text-xs tracking-[0.3em] uppercase" style="color: var(--color-gold);">Daftar Layanan</span>
            </div>

            {{-- Categories Tabs --}}
            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-8 border-b border-[#EDEAE4] pb-5">
                    <button 
                        type="button" 
                        wire:click="$set('selectedCategoryId', null)"
                        class="font-body text-xs px-4 py-2.5 rounded-full border transition-all duration-200"
                        style="{{ is_null($selectedCategoryId) ? 'background-color: var(--color-gold); color: white; border-color: var(--color-gold);' : 'background-color: white; color: var(--color-ink-soft); border-color: var(--color-surface);' }}"
                    >
                        Semua
                    </button>
                    @foreach ($categories as $cat)
                        <button 
                            type="button" 
                            wire:click="$set('selectedCategoryId', {{ $cat->id }})"
                            class="font-body text-xs px-4 py-2.5 rounded-full border transition-all duration-200"
                            style="{{ $selectedCategoryId === $cat->id ? 'background-color: var(--color-gold); color: white; border-color: var(--color-gold);' : 'background-color: white; color: var(--color-ink-soft); border-color: var(--color-surface);' }}"
                        >
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($services as $service)
                    <div class="bg-white flex flex-col group transition-all duration-300 hover:shadow-md" style="border: 1px solid var(--color-surface); border-radius: 10px; padding: 18px;">
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <p class="font-body text-[9px] tracking-[0.2em] uppercase mb-1" style="color: var(--color-gold);">
                                    {{ $service->category?->name ?? 'Layanan' }}
                                </p>
                                <p class="font-display text-lg mb-2 text-[#1C1A18] font-semibold leading-tight">
                                    {{ $service->name }}
                                </p>
                                @if($service->description)
                                    <p class="font-body text-[11px] text-[#9C9690] line-clamp-2 leading-relaxed mb-3">
                                        {{ $service->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex justify-end items-center mt-3 pt-2.5 border-t border-[#FAF9F7]" style="border-top-color: var(--color-surface);">
                                <span class="font-body text-sm font-semibold text-[#1C1A18]">
                                    Rp {{ number_format((float) $service->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 col-span-full text-center" style="border-radius: 12px; border: 1px solid var(--color-surface);">
                        <p class="font-body text-sm" style="color: var(--color-ash);">Belum ada layanan tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    @endif

</div>