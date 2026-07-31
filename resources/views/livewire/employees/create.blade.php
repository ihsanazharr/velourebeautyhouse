<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- Page Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Tambah Karyawan</h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">Daftarkan karyawan atau terapis baru ke dalam sistem.</p>
        </div>
        <a
            href="{{ route('owner.employees.index') }}"
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
        .veloure-form input:not([type=checkbox]),
        .veloure-form select,
        .veloure-form textarea {
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
        .veloure-form input:not([type=checkbox]):focus,
        .veloure-form select:focus,
        .veloure-form textarea:focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12);
            background: white;
        }
        .veloure-form label {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #3A3733;
            margin-bottom: 6px;
            font-weight: 500;
        }
        .veloure-form textarea { resize: vertical; min-height: 90px; }
    </style>

    <form wire:submit="save" class="veloure-form">
        <div class="grid lg:grid-cols-[1fr_320px] gap-6 max-w-5xl">

            {{-- Left: Main Form Content --}}
            <div class="space-y-6">

                {{-- Profil Karyawan Card --}}
                <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px 32px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Profil Karyawan</p>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label>Nama Lengkap <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="name" placeholder="Nama lengkap karyawan" autocomplete="off">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label>Nomor Telepon</label>
                            <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" autocomplete="off">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <label>Jabatan / Peran <span style="color:#B5402C;">*</span></label>
                            <select wire:model.live="role">
                                <option value="hairstylist">Hairstylist</option>
                                <option value="nailist_senior">Nailist Senior</option>
                                <option value="nailist_junior">Nailist Junior</option>
                                <option value="kasir">Kasir</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <label>Tanggal Lahir</label>
                            <input type="date" wire:model="birth_date">
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        <div>
                            <label>Tanggal Masuk <span style="color:#B5402C;">*</span></label>
                            <input type="date" wire:model="hire_date">
                            <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <label>Alamat</label>
                            <textarea wire:model="address" placeholder="Alamat lengkap tinggal…"></textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Akun Login Card (Hanya muncul jika Role = Kasir) --}}
                @if($role === 'kasir')
                    <div class="bg-white transition-all duration-300" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px 32px;">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center" style="background: #F3EBE0;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <circle cx="8" cy="5" r="3" stroke="#B89B6B" stroke-width="1.4"/>
                                    <path d="M2 14c0-2.5 2.7-4.5 6-4.5s6 2 6 4.5" stroke="#B89B6B" stroke-width="1.4" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-body text-sm font-medium" style="color: #1C1A18;">Kredensial Login Kasir</p>
                                <p class="font-body text-xs" style="color: #9C9690;">Kasir memerlukan akun untuk login ke dashboard.</p>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5" style="border-top: 1px dashed #EDEAE4; padding-top: 24px;">
                            <div>
                                <label>Email Akun <span style="color:#B5402C;">*</span></label>
                                <input type="email" wire:model="email" placeholder="kasir@veloure.com" autocomplete="off">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label>Password <span style="color:#B5402C;">*</span></label>
                                <div class="relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Min. 8 karakter">
                                    <button
                                        type="button"
                                        @click="show = !show"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9C9690]"
                                        style="background: none; border: none; cursor: pointer; padding: 4px;"
                                    >
                                        <svg x-show="!show" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/></svg>
                                        <svg x-show="show" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/><line x1="2" y1="2" x2="14" y2="14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Friendly hint for stylists --}}
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white" style="border: 1px solid #EDEAE4;">
                        <svg width="16" height="16" viewBox="0 0 14 14" fill="none" class="mt-0.5 text-[#B89B6B] flex-shrink-0"><circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M7 6v4M7 4.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        <p class="font-body text-xs text-[#9C9690]">
                            Peran terapis/stylist tidak memerlukan akun login. Profil ini hanya digunakan untuk pencatatan absensi, penugasan booking, dan kalkulasi penggajian.
                        </p>
                    </div>
                @endif

            </div>

            {{-- Right: Sidebar Compensation --}}
            <aside class="space-y-4">
                <div class="bg-white sticky top-6" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Penggajian & Status</p>

                    <div class="space-y-4 font-body text-sm">
                        <div>
                            <label class="!text-[10px] !mb-1">Gaji Pokok (Rp) <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="basic_salary" step="50000">
                            <x-input-error :messages="$errors->get('basic_salary')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Uang Makan / Hari (Rp) <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="meal_allowance" step="1000">
                            <x-input-error :messages="$errors->get('meal_allowance')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Lemburan / Jam (Rp) <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="overtime_rate" step="1000">
                            <x-input-error :messages="$errors->get('overtime_rate')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Status Keaktifan <span style="color:#B5402C;">*</span></label>
                            <select wire:model="employment_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="resigned">Resigned</option>
                            </select>
                            <x-input-error :messages="$errors->get('employment_status')" class="mt-1" />
                        </div>
                    </div>

                    <div class="mt-8 pt-4" style="border-top: 1px solid #EDEAE4;">
                        <button
                            type="submit"
                            class="w-full font-body text-xs tracking-[0.15em] uppercase py-4 transition-all duration-200"
                            style="background: #1C1A18; color: #FAF9F7; border: none; border-radius: 8px; cursor: pointer;"
                            onmouseover="this.style.background='#3A3733'"
                            onmouseout="this.style.background='#1C1A18'"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60"
                        >
                            <span wire:loading.remove>Simpan Karyawan</span>
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