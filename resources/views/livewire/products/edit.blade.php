<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Edit Produk
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                {{ $product->sku }} — {{ $product->name }}
            </p>
        </div>
        <a
            href="{{ route('owner.products.index') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-opacity duration-300 hover:opacity-70"
            style="color: #9C9690; text-decoration: underline; text-underline-offset: 4px;"
        >
            ← Kembali
        </a>
    </div>

    @if(session()->has('success'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background: white; border-color: #5C7A5C; color: #3A3733;"
        >
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Kolom Kiri ── --}}
            <div class="flex flex-col gap-6">

                {{-- Identitas Produk --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Identitas Produk</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                SKU <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="sku"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="PRD-YYYYMMDD-001"
                            >
                            @error('sku')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Nama Produk <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Nama produk"
                            >
                            @error('name')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Kategori <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="category"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Hair Care, Nail Care, dll."
                            >
                            @error('category')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Satuan <span style="color: #B5402C;">*</span></label>
                            <select
                                wire:model="unit"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                                <option value="pcs">Pcs</option>
                                <option value="botol">Botol</option>
                                <option value="tube">Tube</option>
                                <option value="sachet">Sachet</option>
                                <option value="liter">Liter</option>
                                <option value="ml">ml</option>
                                <option value="gram">Gram</option>
                                <option value="kg">Kg</option>
                                <option value="set">Set</option>
                            </select>
                            @error('unit')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Supplier</label>
                            <input
                                type="text"
                                wire:model="supplier"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Nama supplier"
                            >
                            @error('supplier')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Deskripsi</label>
                            <textarea
                                wire:model="description"
                                rows="3"
                                class="font-body text-sm w-full outline-none border bg-transparent p-2 resize-none"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Deskripsi produk (opsional)"
                            ></textarea>
                            @error('description')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="flex flex-col gap-6">

                {{-- Stok --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Stok</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Stok Saat Ini <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="current_stock"
                                min="0"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('current_stock')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Stok Minimum <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="minimum_stock"
                                min="0"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('minimum_stock')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Stok Alert <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="alert_stock"
                                min="0"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('alert_stock')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Harga --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Harga</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Harga Modal (Rp) <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="cost_price"
                                min="0"
                                step="500"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('cost_price')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Harga Jual (Rp) <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="selling_price"
                                min="0"
                                step="500"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('selling_price')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Status & Info --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Status</p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-body text-sm" style="color: #1C1A18;">Produk Aktif</p>
                            <p class="font-body text-xs mt-0.5" style="color: #9C9690;">Produk nonaktif tidak akan tampil di transaksi</p>
                        </div>
                        <button
                            type="button"
                            wire:click="$toggle('is_active')"
                            class="relative inline-flex h-6 w-11 items-center transition-colors duration-300 focus:outline-none"
                            style="background: {{ $is_active ? '#1C1A18' : '#EDEAE4' }}; border-radius: 9999px;"
                        >
                            <span
                                class="inline-block h-4 w-4 transform transition-transform duration-300"
                                style="background: white; border-radius: 9999px; margin-left: {{ $is_active ? '24px' : '4px' }};"
                            ></span>
                        </button>
                    </div>

                    <div class="mt-5 pt-4 flex flex-col gap-3" style="border-top: 1px solid #EDEAE4;">
                        <div class="flex justify-between items-center">
                            <span class="font-body text-xs tracking-[0.08em] uppercase" style="color: #9C9690;">Dibuat</span>
                            <span class="font-body text-sm" style="color: #3A3733;">{{ $product->created_at?->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-body text-xs tracking-[0.08em] uppercase" style="color: #9C9690;">Terakhir Diupdate</span>
                            <span class="font-body text-sm" style="color: #3A3733;">{{ $product->updated_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end gap-4 pt-2">
                    <a
                        href="{{ route('owner.products.index') }}"
                        class="font-body text-xs tracking-[0.15em] uppercase py-3.5 px-6 transition-opacity duration-300 hover:opacity-70"
                        style="color: #9C9690; border: 1px solid #EDEAE4; background: white;"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="font-body text-xs tracking-[0.15em] uppercase py-3.5 px-8 transition-opacity duration-300 hover:opacity-80"
                        style="background: #1C1A18; color: #FAF9F7;"
                    >
                        Simpan Perubahan
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>