<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Service Management
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                Kelola layanan VELOURE BEAUTY HOUSE
            </p>
        </div>
        <a
            href="{{ route('owner.services.create') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-opacity duration-300 hover:opacity-80"
            style="
                background: #1C1A18;
                color: #FAF9F7;
                padding: 14px 26px;
                text-decoration: none;
            "
        >
            + Tambah Layanan
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

    <div class="bg-white border mb-4 flex items-center gap-3 px-4" style="border-color: #EDEAE4;">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 6.5C10 8.433 8.433 10 6.5 10C4.567 10 3 8.433 3 6.5C3 4.567 4.567 3 6.5 3C8.433 3 10 4.567 10 6.5ZM9.30884 10.0159C8.53901 10.6318 7.56251 11 6.5 11C4.01472 11 2 8.98528 2 6.5C2 4.01472 4.01472 2 6.5 2C8.98528 2 11 4.01472 11 6.5C11 7.56251 10.6318 8.53901 10.0159 9.30884L12.8536 12.1464C13.0488 12.3417 13.0488 12.6583 12.8536 12.8536C12.6583 13.0488 12.3417 13.0488 12.1464 12.8536L9.30884 10.0159Z" fill="#9C9690" fill-rule="evenodd" clip-rule="evenodd"/>
        </svg>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Cari layanan..."
            class="w-full font-body text-sm py-4 outline-none bg-transparent"
            style="color: #1C1A18;"
        >
    </div>

    <div class="bg-white border overflow-x-auto" style="border-color: #EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse: collapse;">
            <thead>
                <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Kategori</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Nama</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Durasi</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Harga</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Komisi</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Status</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr style="border-bottom: 1px solid #EDEAE4;">
                        <td class="p-4 font-body text-xs tracking-[0.08em]" style="color: #9C9690;">
                            {{ $service->category?->name ?? '—' }}
                        </td>
                        <td class="p-4" style="color: #1C1A18;">
                            {{ $service->name }}
                        </td>
                        <td class="p-4" style="color: #3A3733;">
                            {{ $service->duration_minutes }} menit
                        </td>
                        <td class="p-4" style="color: #1C1A18; font-weight: 500;">
                            Rp {{ number_format($service->price) }}
                        </td>
                        <td class="p-4" style="color: #3A3733;">
                            {{ $service->commission_rate }}%
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <button 
                                    type="button" 
                                    wire:click="toggleActive({{ $service->id }})" 
                                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-0"
                                    style="background-color: {{ $service->is_active ? '#5C7A5C' : '#EDEAE4' }};"
                                >
                                    <span 
                                        class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        style="transform: translateX({{ $service->is_active ? '16px' : '0px' }});"
                                    ></span>
                                </button>
                                <span class="font-body text-xs {{ $service->is_active ? 'text-[#5C7A5C]' : 'text-[#9C9690]' }}" style="font-weight: 500;">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4">
                            <a
                                href="{{ route('owner.services.edit', $service->id) }}"
                                class="font-body text-xs tracking-[0.1em] uppercase transition-colors duration-300"
                                style="color: #1C1A18; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: #EDEAE4;"
                                onmouseover="this.style.textDecorationColor='#B89B6B'"
                                onmouseout="this.style.textDecorationColor='#EDEAE4'"
                            >
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 font-body text-sm text-center" style="color: #9C9690;">
                            Belum ada layanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 font-body text-sm" style="color: #9C9690;">
        {{ $services->links() }}
    </div>
</div>