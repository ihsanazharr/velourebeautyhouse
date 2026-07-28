<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Sistem</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Pengaturan
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                Kelola foto banner landing page, QRIS pembayaran, dan jam kerja kantor.
            </p>
        </div>
    </div>

    @if(session()->has('success'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background: white; border-color: #5C7A5C; color: #3A3733;"
        >
            {{ session('success') }}
        </div>
    @endif

    <style>
        .veloure-form :where(input:not([type=checkbox]), select, textarea) {
            border-radius: 8px;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: #FAF9F7;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .veloure-form :where(input:not([type=checkbox]), select, textarea):focus {
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
        .img-card {
            border: 1px dashed #EDEAE4;
            border-radius: 10px;
            background: #FAF9F7;
            padding: 20px;
            text-align: center;
            position: relative;
        }
    </style>

    <form wire:submit="save" class="veloure-form max-w-5xl">
        <div class="grid lg:grid-cols-2 gap-8 items-start">
            
            {{-- COLUMN 1: IMAGE MANAGEMENT --}}
            <div class="space-y-6">
                
                {{-- Hero Banner Image Card --}}
                <div class="bg-white border p-6 sm:p-8" style="border-color: #EDEAE4; border-radius: 12px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">Banner Landing Page</p>
                    
                    <div class="space-y-4">
                        @if ($hero_image_file)
                            <div class="aspect-[16/9] w-full overflow-hidden border rounded-lg" style="border-color: #EDEAE4;">
                                <img src="{{ $hero_image_file->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                            <p class="font-body text-xs text-[#5C7A5C] text-center">Gambar baru siap disimpan.</p>
                        @elseif ($current_hero_image)
                            <div class="aspect-[16/9] w-full overflow-hidden border rounded-lg relative group" style="border-color: #EDEAE4;">
                                <img src="{{ asset('storage/' . $current_hero_image) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button 
                                        type="button" 
                                        wire:click="deleteHeroImage" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus gambar banner ini?"
                                        class="px-4 py-2 font-body text-xs text-white uppercase tracking-[0.1em] hover:bg-[#B5402C] transition-colors duration-200"
                                        style="background: #1C1A18;"
                                    >
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="img-card py-10">
                                <svg class="mx-auto h-8 w-8 text-[#9C9690] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="block font-body text-xs text-[#9C9690] mb-2">Default Unsplash Image Active</span>
                            </div>
                        @endif

                        <div>
                            <input type="file" wire:model="hero_image_file" id="hero_image_input" class="hidden">
                            <label 
                                for="hero_image_input" 
                                class="w-full text-center block font-body text-xs tracking-[0.1em] uppercase cursor-pointer py-3 transition-colors duration-200"
                                style="border: 1px solid #1C1A18; color: #1C1A18; background: transparent; border-radius: 8px;"
                                onmouseover="this.style.background='#1C1A18'; this.style.color='#FAF9F7';"
                                onmouseout="this.style.background='transparent'; this.style.color='#1C1A18';"
                            >
                                {{ $current_hero_image || $hero_image_file ? 'Ganti Foto Banner' : 'Unggah Foto Banner' }}
                            </label>
                            <x-input-error :messages="$errors->get('hero_image_file')" class="mt-2" />
                            <div wire:loading wire:target="hero_image_file" class="text-xs text-[#9C9690] mt-2">Mengunggah file...</div>
                        </div>
                    </div>
                </div>

                {{-- QRIS Payment Image Card --}}
                <div class="bg-white border p-6 sm:p-8" style="border-color: #EDEAE4; border-radius: 12px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">QRIS Pembayaran</p>
                    
                    <div class="space-y-4">
                        @if ($qris_image_file)
                            <div class="aspect-square max-w-[240px] mx-auto overflow-hidden border rounded-lg" style="border-color: #EDEAE4;">
                                <img src="{{ $qris_image_file->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                            <p class="font-body text-xs text-[#5C7A5C] text-center">Gambar baru siap disimpan.</p>
                        @elseif ($current_qris_image)
                            <div class="aspect-square max-w-[240px] mx-auto overflow-hidden border rounded-lg relative group" style="border-color: #EDEAE4;">
                                <img src="{{ asset('storage/' . $current_qris_image) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button 
                                        type="button" 
                                        wire:click="deleteQrisImage" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus QRIS ini?"
                                        class="px-4 py-2 font-body text-xs text-white uppercase tracking-[0.1em] hover:bg-[#B5402C] transition-colors duration-200"
                                        style="background: #1C1A18;"
                                    >
                                        Hapus QRIS
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="img-card max-w-[240px] mx-auto aspect-square flex flex-col items-center justify-center">
                                <svg class="h-8 w-8 text-[#9C9690] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span class="block font-body text-xs text-[#9C9690]">QRIS Belum Diunggah</span>
                            </div>
                        @endif

                        <div>
                            <input type="file" wire:model="qris_image_file" id="qris_image_input" class="hidden">
                            <label 
                                for="qris_image_input" 
                                class="w-full text-center block font-body text-xs tracking-[0.1em] uppercase cursor-pointer py-3 transition-colors duration-200"
                                style="border: 1px solid #1C1A18; color: #1C1A18; background: transparent; border-radius: 8px;"
                                onmouseover="this.style.background='#1C1A18'; this.style.color='#FAF9F7';"
                                onmouseout="this.style.background='transparent'; this.style.color='#1C1A18';"
                            >
                                {{ $current_qris_image || $qris_image_file ? 'Ganti QRIS' : 'Unggah QRIS' }}
                            </label>
                            <x-input-error :messages="$errors->get('qris_image_file')" class="mt-2" />
                            <div wire:loading wire:target="qris_image_file" class="text-xs text-[#9C9690] mt-2">Mengunggah file...</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMN 2: SYSTEM CONFIGURATIONS --}}
            <div class="space-y-6">
                
                {{-- General Info Card --}}
                <div class="bg-white border p-6 sm:p-8" style="border-color: #EDEAE4; border-radius: 12px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Informasi Salon</p>

                    <div class="space-y-4">
                        <div>
                            <label>Nama Perusahaan / Salon</label>
                            <input type="text" wire:model="company_name">
                            <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                        </div>

                        <div>
                            <label>Alamat</label>
                            <textarea wire:model="address" rows="3"></textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-1" />
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label>Nomor Telepon</label>
                                <input type="text" wire:model="phone">
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>
                            <div>
                                <label>Nomor WhatsApp</label>
                                <input type="text" wire:model="whatsapp_number">
                                <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-1" />
                            </div>
                        </div>

                        <div>
                            <label>Email Salon</label>
                            <input type="email" wire:model="email">
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                    </div>
                </div>

                {{-- Operational Settings Card --}}
                <div class="bg-white border p-6 sm:p-8" style="border-color: #EDEAE4; border-radius: 12px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Operasional & Jam Kerja</p>

                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label>Jam Buka</label>
                                <input type="time" wire:model="open_time">
                                <x-input-error :messages="$errors->get('open_time')" class="mt-1" />
                            </div>
                            <div>
                                <label>Jam Tutup</label>
                                <input type="time" wire:model="close_time">
                                <x-input-error :messages="$errors->get('close_time')" class="mt-1" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label>Toleransi Terlambat (Menit)</label>
                                <input type="number" wire:model="late_tolerance_minutes">
                                <x-input-error :messages="$errors->get('late_tolerance_minutes')" class="mt-1" />
                            </div>
                            <div>
                                <label>Denda Terlambat (Rp)</label>
                                <input type="number" wire:model="late_penalty_amount">
                                <x-input-error :messages="$errors->get('late_penalty_amount')" class="mt-1" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label>Uang Makan Harian (Rp)</label>
                                <input type="number" wire:model="meal_allowance_amount">
                                <x-input-error :messages="$errors->get('meal_allowance_amount')" class="mt-1" />
                            </div>
                            <div>
                                <label>Tarif Lembur per Jam (Rp)</label>
                                <input type="number" wire:model="overtime_rate">
                                <x-input-error :messages="$errors->get('overtime_rate')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" 
                        style="background: #1C1A18; color: #FAF9F7;"
                    >
                        Simpan Pengaturan
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>
