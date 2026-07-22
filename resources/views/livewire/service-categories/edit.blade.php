<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Service</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Edit Kategori</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">{{ $category->name }}</p>
        </div>

        <a href="{{ route('owner.service-categories.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

    <form wire:submit="save" class="max-w-2xl">
        <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
            <div class="mb-6">
                <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Nama Kategori</label>
                <input type="text" wire:model="name" class="font-body text-sm w-full border p-3 focus:outline-none focus:border-[#B89B6B]" style="border-color:#EDEAE4; color:#1C1A18;">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <label class="inline-flex items-center gap-3 font-body text-sm cursor-pointer mb-8" style="color:#3A3733;">
                <input type="checkbox" wire:model="is_active" class="rounded-none border-[#EDEAE4] text-[#B89B6B] focus:ring-[#B89B6B]">
                Kategori aktif
            </label>

            <button type="submit" class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7;">
                Update Kategori
            </button>
        </div>
    </form>
</div>
