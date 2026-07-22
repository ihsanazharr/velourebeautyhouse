<div class="min-h-screen" style="background-color:#FAF9F7;">
    <style>
        .customer-booking {
            --paper: #FAF9F7;
            --surface: #EDEAE4;
            --ash: #9C9690;
            --ink: #1C1A18;
            --ink-soft: #3A3733;
            --gold: #B89B6B;
        }

        .customer-booking :where(input, select, textarea) {
            border-radius: 0;
            border: 1px solid var(--surface);
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: var(--ink);
            background: white;
            width: 100%;
            box-shadow: none;
        }

        .customer-booking :where(input, select, textarea):focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 1px var(--gold);
        }

        .customer-booking label {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <div class="customer-booking max-w-6xl mx-auto px-5 sm:px-6 lg:px-10 py-8 lg:py-12">
        <div class="flex items-center gap-3 mb-3">
            <span class="h-px w-8" style="background-color:#B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Customer Booking</span>
        </div>

        <div class="grid lg:grid-cols-[1fr_360px] gap-8 items-start">
            <div>
                <h1 class="font-display text-4xl sm:text-5xl leading-tight mb-4" style="color:#1C1A18;">
                    Lengkapi detail perawatan Anda
                </h1>
                <p class="font-body text-sm sm:text-base leading-relaxed max-w-2xl mb-8" style="color:#9C9690;">
                    Pilih layanan untuk setiap orang yang datang. Booking akan masuk sebagai pending sampai tim Veloure mengonfirmasi jadwal Anda.
                </p>

                @if (session('success'))
                    <div class="bg-white border px-5 py-4 mb-6 font-body text-sm" style="border-color:#B89B6B; color:#3A3733;">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save" class="space-y-6">
                    <section class="bg-white border p-5 sm:p-7" style="border-color:#EDEAE4;">
                        <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                            <div>
                                <p class="font-body text-[11px] tracking-[0.22em] uppercase mb-1" style="color:#B89B6B;">Step 1</p>
                                <h2 class="font-display text-2xl" style="color:#1C1A18;">Jadwal</h2>
                            </div>
                            <a href="{{ url('/') }}" wire:navigate class="font-body text-xs tracking-[0.12em] uppercase underline underline-offset-4" style="color:#9C9690;">
                                Ganti Jam
                            </a>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label for="date" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Tanggal</label>
                                <input id="date" type="date" wire:model="date">
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <div>
                                <label for="time" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Jam</label>
                                <input id="time" type="time" wire:model="time">
                                <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            </div>

                            <div>
                                <label for="peopleCount" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Jumlah Orang</label>
                                <select id="peopleCount" wire:model.live="peopleCount">
                                    @for ($count = 1; $count <= 6; $count++)
                                        <option value="{{ $count }}">{{ $count }} orang</option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('peopleCount')" class="mt-2" />
                            </div>
                        </div>
                    </section>

                    <section class="bg-white border p-5 sm:p-7" style="border-color:#EDEAE4;">
                        <div class="mb-6">
                            <p class="font-body text-[11px] tracking-[0.22em] uppercase mb-1" style="color:#B89B6B;">Step 2</p>
                            <h2 class="font-display text-2xl" style="color:#1C1A18;">Orang & Layanan</h2>
                        </div>

                        <div class="space-y-5">
                            @foreach ($participants as $index => $participant)
                                <div class="border p-4 sm:p-5" style="border-color:#EDEAE4; background-color:#FAF9F7;">
                                    <div class="grid md:grid-cols-[220px_1fr] gap-5">
                                        <div>
                                            <label for="participant-{{ $index }}" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">
                                                Orang {{ $index + 1 }}
                                            </label>
                                            <input
                                                id="participant-{{ $index }}"
                                                type="text"
                                                wire:model="participants.{{ $index }}.name"
                                                placeholder="{{ $index === 0 ? 'Nama Anda' : 'Nama teman/keluarga' }}"
                                            >
                                            <x-input-error :messages="$errors->get('participants.' . $index . '.name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <p class="font-body text-xs tracking-[0.15em] uppercase mb-3" style="color:#3A3733;">Pilih Service</p>
                                            <div class="grid sm:grid-cols-2 gap-3">
                                                @forelse ($services as $service)
                                                    <label class="flex gap-3 p-3 border bg-white cursor-pointer transition-colors duration-300 hover:border-[#B89B6B]" style="border-color:#EDEAE4;">
                                                        <input
                                                            type="checkbox"
                                                            value="{{ $service->id }}"
                                                            wire:model.live="participants.{{ $index }}.service_ids"
                                                            class="mt-1 h-4 w-4 shrink-0 rounded-none border-[#EDEAE4] text-[#B89B6B] focus:ring-[#B89B6B]"
                                                            style="width:1rem; padding:0;"
                                                        >
                                                        <span class="min-w-0">
                                                            <span class="block font-body text-sm" style="color:#1C1A18;">{{ $service->name }}</span>
                                                            <span class="block font-body text-[11px] mt-1" style="color:#9C9690;">
                                                                {{ $service->category?->name ?? 'Layanan' }} &middot; {{ $service->duration_minutes }} menit
                                                            </span>
                                                            <span class="block font-body text-xs mt-1" style="color:#B89B6B;">
                                                                Rp {{ number_format((float) $service->price, 0, ',', '.') }}
                                                            </span>
                                                        </span>
                                                    </label>
                                                @empty
                                                    <div class="sm:col-span-2 border p-4 bg-white font-body text-sm" style="border-color:#EDEAE4; color:#9C9690;">
                                                        Belum ada layanan tersedia.
                                                    </div>
                                                @endforelse
                                            </div>
                                            <x-input-error :messages="$errors->get('participants.' . $index . '.service_ids')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="bg-white border p-5 sm:p-7" style="border-color:#EDEAE4;">
                        <label for="notes" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Catatan Tambahan</label>
                        <textarea id="notes" rows="4" wire:model="notes" placeholder="Contoh: request nail art simple, datang bersama anak, atau catatan alergi produk."></textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </section>

                    <section class="bg-white border p-5 sm:p-7" style="border-color:#EDEAE4;">
                        <div class="mb-6">
                            <p class="font-body text-[11px] tracking-[0.22em] uppercase mb-1" style="color:#B89B6B;">Step 3</p>
                            <h2 class="font-display text-2xl" style="color:#1C1A18;">Pembayaran QRIS</h2>
                            <p class="font-body text-sm leading-relaxed mt-2" style="color:#9C9690;">
                                Scan QRIS, lakukan pembayaran sesuai estimasi total, lalu upload foto bukti pembayaran.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-[260px_1fr] gap-6 items-start">
                            <div class="border p-4 bg-[#FAF9F7]" style="border-color:#EDEAE4;">
                                @if ($qrisImage)
                                    <img
                                        src="{{ asset('storage/' . $qrisImage) }}"
                                        alt="QRIS Veloure Beauty House"
                                        class="w-full aspect-square object-contain bg-white"
                                    >
                                @else
                                    <div class="aspect-square bg-white border flex items-center justify-center text-center p-5" style="border-color:#EDEAE4;">
                                        <div>
                                            <p class="font-display text-4xl mb-3" style="color:#1C1A18;">QRIS</p>
                                            <p class="font-body text-xs leading-relaxed" style="color:#9C9690;">
                                                Gambar QRIS belum diatur di settings.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="border p-4 mb-5" style="border-color:#EDEAE4; background-color:#FAF9F7;">
                                    <p class="font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#9C9690;">Total Dibayar</p>
                                    <p class="font-display text-3xl" style="color:#1C1A18;">Rp {{ number_format((float) $selectedTotal, 0, ',', '.') }}</p>
                                </div>

                                <label for="proofImage" class="block text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Upload Bukti Pembayaran</label>
                                <input id="proofImage" type="file" wire:model="proofImage" accept="image/*">
                                <x-input-error :messages="$errors->get('proofImage')" class="mt-2" />

                                <div wire:loading wire:target="proofImage" class="font-body text-xs mt-3" style="color:#9C9690;">
                                    Mengunggah bukti pembayaran...
                                </div>

                                @if ($proofImage)
                                    <div class="mt-4 border p-3 inline-block bg-white" style="border-color:#EDEAE4;">
                                        <img
                                            src="{{ $proofImage->temporaryUrl() }}"
                                            alt="Preview bukti pembayaran"
                                            class="w-44 max-w-full aspect-[3/4] object-cover"
                                        >
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <div class="lg:hidden bg-white border p-5" style="border-color:#EDEAE4;">
                        <p class="font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#9C9690;">Estimasi Total</p>
                        <p class="font-display text-3xl" style="color:#1C1A18;">Rp {{ number_format((float) $selectedTotal, 0, ',', '.') }}</p>
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto inline-flex items-center justify-center font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-colors duration-300 hover:opacity-90 disabled:opacity-60"
                        style="background-color:#1C1A18; color:#FAF9F7;"
                    >
                        Kirim Booking
                    </button>
                </form>
            </div>

            <aside class="hidden lg:block sticky top-8 bg-white border p-6" style="border-color:#EDEAE4;">
                <p class="font-body text-[11px] tracking-[0.22em] uppercase mb-2" style="color:#B89B6B;">Ringkasan</p>
                <h2 class="font-display text-3xl mb-6" style="color:#1C1A18;">Booking Anda</h2>

                <div class="space-y-4 font-body text-sm" style="color:#3A3733;">
                    <div class="flex justify-between gap-4 border-b pb-4" style="border-color:#EDEAE4;">
                        <span style="color:#9C9690;">Tanggal</span>
                        <span class="text-right">{{ $date ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-4 border-b pb-4" style="border-color:#EDEAE4;">
                        <span style="color:#9C9690;">Jam</span>
                        <span>{{ $time ?: '-' }} WIB</span>
                    </div>
                    <div class="flex justify-between gap-4 border-b pb-4" style="border-color:#EDEAE4;">
                        <span style="color:#9C9690;">Jumlah orang</span>
                        <span>{{ $peopleCount }} orang</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t" style="border-color:#EDEAE4;">
                    <p class="font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#9C9690;">Estimasi Total</p>
                    <p class="font-display text-4xl" style="color:#1C1A18;">Rp {{ number_format((float) $selectedTotal, 0, ',', '.') }}</p>
                    <p class="font-body text-xs leading-relaxed mt-3" style="color:#9C9690;">
                        Harga final dapat disesuaikan setelah konsultasi langsung di salon.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>
