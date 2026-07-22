<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">
    @php
        $customerRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
    @endphp

    {{-- Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Customer</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Edit Customer</h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">{{ $customer->name }} &middot; {{ $customer->phone }}</p>
        </div>
        <a
            href="{{ route($customerRoutePrefix . '.customers.index') }}"
            wire:navigate
            class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4"
            style="color: #9C9690; text-decoration-color: #EDEAE4;"
            onmouseover="this.style.textDecorationColor='#B89B6B'"
            onmouseout="this.style.textDecorationColor='#EDEAE4'"
        >
            ← Kembali
        </a>
    </div>

    <style>
        .vc-form input[type=text],
        .vc-form input[type=email],
        .vc-form input[type=date],
        .vc-form input[type=password],
        .vc-form textarea {
            border-radius: 8px;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: #FAF9F7;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .vc-form input[type=text]:focus,
        .vc-form input[type=email]:focus,
        .vc-form input[type=date]:focus,
        .vc-form input[type=password]:focus,
        .vc-form textarea:focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12);
            background: white;
        }
        .vc-form textarea { resize: vertical; min-height: 90px; }
    </style>

    <form wire:submit="save" class="vc-form">
        <div class="grid lg:grid-cols-[1fr_300px] gap-6">

            {{-- Left Column --}}
            <div class="space-y-5">

                {{-- Profil Card --}}
                <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px 32px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Profil Customer</p>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Nama Lengkap <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="name" autocomplete="name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Nomor HP <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="phone" autocomplete="tel">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Email</label>
                            <input type="email" wire:model="email" autocomplete="email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Tanggal Lahir</label>
                            <input type="date" wire:model="birth_date">
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-3 cursor-pointer" style="color: #3A3733;">
                                <div x-data class="relative">
                                    <input type="checkbox" wire:model="is_active" id="is_active_edit_toggle" class="sr-only">
                                    <label
                                        for="is_active_edit_toggle"
                                        class="block w-10 h-5 rounded-full cursor-pointer transition-colors duration-200"
                                        :style="$wire.is_active ? 'background:#5C7A5C;' : 'background:#EDEAE4;'"
                                    >
                                        <span class="block w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"
                                            :style="$wire.is_active ? 'transform: translateX(22px); margin-top:2px;' : 'transform: translateX(2px); margin-top:2px;'"
                                        ></span>
                                    </label>
                                </div>
                                <span class="font-body text-sm">Customer Aktif</span>
                            </label>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Catatan Internal</label>
                            <textarea wire:model="notes" placeholder="Preferensi layanan, alergi produk, atau catatan lainnya…"></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Account Section --}}
                @if($customer->user)
                    {{-- Already has account --}}
                    <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px 32px;">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: #EAF1EA;">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><circle cx="9" cy="6" r="3.5" stroke="#5C7A5C" stroke-width="1.4"/><path d="M2.5 16c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5" stroke="#5C7A5C" stroke-width="1.4" stroke-linecap="round"/></svg>
                            </div>
                            <div>
                                <p class="font-body text-sm font-medium" style="color: #1C1A18;">Akun Login Aktif</p>
                                <p class="font-body text-xs mt-0.5" style="color: #5C7A5C;">{{ $customer->user->email }}</p>
                            </div>
                            <span class="ml-auto font-body text-xs tracking-[0.08em] uppercase px-2.5 py-1" style="background: #EAF1EA; color: #5C7A5C; border-radius: 4px;">Terhubung</span>
                        </div>
                    </div>
                @else
                    {{-- No account — show create account toggle --}}
                    <div class="bg-white overflow-hidden" style="border: 1px solid #EDEAE4; border-radius: 12px;" x-data>
                        <button
                            type="button"
                            @click="$wire.create_account = !$wire.create_account"
                            class="w-full flex items-center justify-between text-left transition-colors duration-200"
                            style="padding: 22px 32px;"
                            :style="$wire.create_account ? 'background: #FAF9F7;' : 'background: white;'"
                        >
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" style="background: #F3EBE0;">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5" r="3" stroke="#B89B6B" stroke-width="1.4"/><path d="M2 14c0-2.5 2.7-4.5 6-4.5s6 2 6 4.5" stroke="#B89B6B" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div>
                                    <p class="font-body text-sm font-medium" style="color: #1C1A18;">Buat Akun Login</p>
                                    <p class="font-body text-xs mt-0.5" style="color: #9C9690;">Customer belum memiliki akun untuk booking online</p>
                                </div>
                            </div>
                            <div
                                class="w-11 h-6 rounded-full flex-shrink-0 transition-colors duration-200 relative"
                                :style="$wire.create_account ? 'background:#B89B6B;' : 'background:#EDEAE4;'"
                            >
                                <span class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all duration-200"
                                    :style="$wire.create_account ? 'left: 26px;' : 'left: 4px;'"
                                ></span>
                            </div>
                        </button>

                        <div
                            x-show="$wire.create_account"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            style="padding: 0 32px 28px;"
                        >
                            <div style="border-top: 1px dashed #EDEAE4; padding-top: 24px;" class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Email Akun <span style="color:#B5402C;">*</span></label>
                                    <input type="email" wire:model="account_email" placeholder="email untuk login" autocomplete="off">
                                    <x-input-error :messages="$errors->get('account_email')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Password <span style="color:#B5402C;">*</span></label>
                                    <div class="relative" x-data="{ show: false }">
                                        <input :type="show ? 'text' : 'password'" wire:model="account_password" placeholder="Min. 8 karakter" autocomplete="new-password">
                                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2" style="color: #9C9690; background: none; border: none; cursor: pointer; padding: 4px;">
                                            <svg x-show="!show" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                                            <svg x-show="show" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/><line x1="2" y1="2" x2="14" y2="14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('account_password')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right: Summary sidebar --}}
            <aside>
                <div class="bg-white sticky top-6" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Ringkasan</p>

                    <div class="space-y-4 font-body text-sm">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] mb-1" style="color: #9C9690;">Status</p>
                            <span
                                x-data
                                class="font-body text-xs tracking-[0.1em] uppercase px-2.5 py-1 inline-block"
                                :style="$wire.is_active ? 'background:#EAF1EA; color:#5C7A5C; border-radius:4px;' : 'background:#EDEAE4; color:#9C9690; border-radius:4px;'"
                                x-text="$wire.is_active ? 'Aktif' : 'Nonaktif'"
                            ></span>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] mb-1" style="color: #9C9690;">Akun Login</p>
                            @if($customer->user)
                                <span class="font-body text-xs tracking-[0.08em] uppercase px-2.5 py-1 inline-block" style="background: #EAF1EA; color: #5C7A5C; border-radius: 4px;">Terhubung</span>
                            @else
                                <span
                                    x-data
                                    class="font-body text-xs tracking-[0.08em] uppercase px-2.5 py-1 inline-block"
                                    :style="$wire.create_account ? 'background:#FBF3E8; color:#B89B6B; border-radius:4px;' : 'background:#EDEAE4; color:#9C9690; border-radius:4px;'"
                                    x-text="$wire.create_account ? 'Akan Dibuat' : 'Belum Ada'"
                                ></span>
                            @endif
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] mb-1" style="color: #9C9690;">Last Visit</p>
                            <p style="color: #3A3733;">{{ $customer->last_visit_at?->format('d M Y H:i') ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] mb-1" style="color: #9C9690;">Terdaftar</p>
                            <p style="color: #3A3733;">{{ $customer->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full font-body text-xs tracking-[0.15em] uppercase py-4 transition-all duration-200"
                            style="background: #1C1A18; color: #FAF9F7; border: none; border-radius: 8px; cursor: pointer;"
                            onmouseover="this.style.background='#3A3733'"
                            onmouseout="this.style.background='#1C1A18'"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60"
                        >
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <svg class="animate-spin w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4z"/></svg>
                                Menyimpan…
                            </span>
                        </button>
                    </div>
                </div>
            </aside>

        </div>
    </form>
</div>
