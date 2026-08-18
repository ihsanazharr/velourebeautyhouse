<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- Page Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Tambah Produk</h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">Daftarkan produk ritel baru ke dalam inventaris salon.</p>
        </div>
        <a
            href="{{ route('owner.products.index') }}"
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
        <div class="grid lg:grid-cols-[1fr_320px] gap-6 w-full">

            {{-- Left: Main Form Content --}}
            <div class="space-y-6">

                {{-- Identitas Produk Card --}}
                <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px 32px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Identitas Produk</p>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label>Kode SKU <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="sku" placeholder="PRD-YYYYMMDD-XXX" autocomplete="off">
                            <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                        </div>

                        <div>
                            <label>Kategori <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="category" placeholder="Hair, Nail, Lash, dll" autocomplete="off">
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <label>Nama Produk <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="name" placeholder="Nama lengkap produk ritel" autocomplete="off">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label>Satuan (Unit) <span style="color:#B5402C;">*</span></label>
                            <input type="text" wire:model="unit" placeholder="Pcs, Bottle, Tube, dll" autocomplete="off">
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div>
                            <label>Pemasok (Supplier)</label>
                            <input type="text" wire:model="supplier" placeholder="Nama supplier/pemasok" autocomplete="off">
                            <x-input-error :messages="$errors->get('supplier')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <label>Deskripsi Produk</label>
                            <textarea wire:model="description" placeholder="Deskripsi detail atau instruksi penggunaan produk…"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Sidebar Inventory & Pricing --}}
            <aside class="space-y-4">
                <div class="bg-white sticky top-6" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Stok & Harga</p>

                    <div class="space-y-4 font-body text-sm">
                        <div>
                            <label class="!text-[10px] !mb-1">Harga Modal (Rp) <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="cost_price" placeholder="Harga beli produk">
                            <x-input-error :messages="$errors->get('cost_price')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Harga Jual (Rp) <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="selling_price" placeholder="Harga retail ke customer">
                            <x-input-error :messages="$errors->get('selling_price')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Stok Saat Ini <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="current_stock">
                            <x-input-error :messages="$errors->get('current_stock')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Minimum Stok <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="minimum_stock">
                            <x-input-error :messages="$errors->get('minimum_stock')" class="mt-1" />
                        </div>

                        <div>
                            <label class="!text-[10px] !mb-1">Alert Stok <span style="color:#B5402C;">*</span></label>
                            <input type="number" wire:model="alert_stock">
                            <x-input-error :messages="$errors->get('alert_stock')" class="mt-1" />
                        </div>

                        <div>
                            <label class="inline-flex items-center gap-3 cursor-pointer mt-2" style="color: #3A3733;">
                                <div x-data class="relative">
                                    <input type="checkbox" wire:model="is_active" id="is_active_product_toggle" class="sr-only">
                                    <label
                                        for="is_active_product_toggle"
                                        class="block w-10 h-5 rounded-full cursor-pointer transition-colors duration-200"
                                        :style="$wire.is_active ? 'background:#5C7A5C;' : 'background:#EDEAE4;'"
                                    >
                                        <span class="block w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"
                                            :style="$wire.is_active ? 'transform: translateX(22px); margin-top:2px;' : 'transform: translateX(2px); margin-top:2px;'"
                                        ></span>
                                    </label>
                                </div>
                                <span class="font-body text-xs">Produk Aktif</span>
                            </label>
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
                            <span wire:loading.remove>Simpan Produk</span>
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