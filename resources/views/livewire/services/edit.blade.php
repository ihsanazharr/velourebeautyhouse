<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Service</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Edit Layanan</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">Perbarui detail layanan, durasi, harga, dan status booking.</p>
        </div>

        <a href="{{ route('owner.services.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

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
        .veloure-form input[type=checkbox] {
            width: 1rem;
            height: 1rem;
            border-radius: 4px;
            border: 1px solid #EDEAE4;
            color: #B89B6B;
            cursor: pointer;
        }
        .veloure-form input[type=checkbox]:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(184,155,107,0.12);
        }
    </style>

    <form wire:submit="save" class="veloure-form max-w-5xl">
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color:#B89B6B;">Informasi Layanan</p>

                <div class="space-y-5">
                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Kategori</label>
                        <select wire:model="service_category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_category_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Nama Layanan</label>
                        <input type="text" wire:model="name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Deskripsi</label>
                        <textarea wire:model="description" rows="5"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color:#B89B6B;">Harga & Operasional</p>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Durasi</label>
                        <input type="number" min="1" wire:model="duration_minutes">
                        <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Harga</label>
                        <input type="number" min="0" wire:model="price">
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Komisi Default (%)</label>
                        <input type="number" step="0.01" min="0" wire:model="commission_rate">
                        <x-input-error :messages="$errors->get('commission_rate')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <label class="inline-flex items-center gap-3 cursor-pointer" style="color: #3A3733;">
                            <div x-data class="relative">
                                <input type="checkbox" wire:model="requires_booking" id="requires_booking_toggle" class="sr-only">
                                <label
                                    for="requires_booking_toggle"
                                    class="block w-10 h-5 rounded-full cursor-pointer transition-colors duration-200"
                                    :style="$wire.requires_booking ? 'background:#5C7A5C;' : 'background:#EDEAE4;'"
                                >
                                    <span class="block w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"
                                        :style="$wire.requires_booking ? 'transform: translateX(22px); margin-top:2px;' : 'transform: translateX(2px); margin-top:2px;'"
                                    ></span>
                                </label>
                            </div>
                            <span class="font-body text-sm">Memerlukan booking</span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <label class="inline-flex items-center gap-3 cursor-pointer" style="color: #3A3733;">
                            <div x-data class="relative">
                                <input type="checkbox" wire:model="is_active" id="is_active_toggle" class="sr-only">
                                <label
                                    for="is_active_toggle"
                                    class="block w-10 h-5 rounded-full cursor-pointer transition-colors duration-200"
                                    :style="$wire.is_active ? 'background:#5C7A5C;' : 'background:#EDEAE4;'"
                                >
                                    <span class="block w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"
                                        :style="$wire.is_active ? 'transform: translateX(22px); margin-top:2px;' : 'transform: translateX(2px); margin-top:2px;'"
                                    ></span>
                                </label>
                            </div>
                            <span class="font-body text-sm">Layanan aktif</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7;">
                Update Layanan
            </button>
        </div>
    </form>
</div>
