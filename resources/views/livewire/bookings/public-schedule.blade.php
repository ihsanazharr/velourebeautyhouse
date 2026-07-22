<div>

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
                        <div class="flex justify-between items-center mt-3 pt-2.5 border-t border-[#FAF9F7]" style="border-top-color: var(--color-surface);">
                            <span class="font-body text-[10px] text-[#9C9690]">Mulai dari</span>
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

</div>