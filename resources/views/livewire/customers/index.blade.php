<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">
    @php
        $customerRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
    @endphp

    {{-- Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Manajemen</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Customer</h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">Kelola data dan akun customer VELOURÉ.</p>
        </div>
        <a
            href="{{ route($customerRoutePrefix . '.customers.create') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-200 inline-flex items-center gap-2"
            style="background: #1C1A18; color: #FAF9F7; padding: 13px 22px; text-decoration: none; border-radius: 8px;"
            onmouseover="this.style.background='#3A3733'"
            onmouseout="this.style.background='#1C1A18'"
        >
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Tambah Customer
        </a>
    </div>

    {{-- Flash --}}
    @if(session()->has('success'))
        <div class="font-body text-sm mb-6 px-5 py-4 border-l-2 flex items-center gap-3"
            style="background: white; border-color: #5C7A5C; color: #3A3733; border-radius: 0 8px 8px 0;">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="7.5" r="7.5" fill="#5C7A5C"/><path d="M4.5 7.5L6.5 10L10.5 5" stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="bg-white mb-5 flex items-center gap-3 px-4" style="border: 1px solid #EDEAE4; border-radius: 10px;">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
            <path d="M10 6.5C10 8.433 8.433 10 6.5 10C4.567 10 3 8.433 3 6.5C3 4.567 4.567 3 6.5 3C8.433 3 10 4.567 10 6.5ZM9.309 10.016C8.539 10.632 7.563 11 6.5 11C4.015 11 2 8.985 2 6.5C2 4.015 4.015 2 6.5 2C8.985 2 11 4.015 11 6.5C11 7.563 10.632 8.539 10.016 9.309L12.854 12.146C13.049 12.342 13.049 12.658 12.854 12.854C12.658 13.049 12.342 13.049 12.146 12.854L9.309 10.016Z" fill="#9C9690" fill-rule="evenodd" clip-rule="evenodd"/>
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari nama atau nomor HP..."
            class="w-full font-body text-sm py-3.5 outline-none bg-transparent"
            style="color: #1C1A18;"
        >
    </div>

    {{-- Table --}}
    <div class="bg-white overflow-hidden" style="border: 1px solid #EDEAE4; border-radius: 12px;">
        <div class="overflow-x-auto">
            <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse: collapse;">
                <thead>
                    <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Nama</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Telepon</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Email</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Last Visit</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Akun Login</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Status</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr
                            style="border-bottom: 1px solid #EDEAE4; transition: background 0.15s;"
                            onmouseover="this.style.background='#FAFAF8'"
                            onmouseout="this.style.background='transparent'"
                        >
                            {{-- Name + avatar initial --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-body text-xs font-semibold flex-shrink-0"
                                        style="background: #F3EBE0; color: #B89B6B;"
                                    >
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <span style="color: #1C1A18; font-weight: 500;">{{ $customer->name }}</span>
                                </div>
                            </td>

                            <td class="p-4 font-body text-sm" style="color: #3A3733;">
                                {{ $customer->phone }}
                            </td>

                            <td class="p-4 font-body text-sm" style="color: #9C9690;">
                                {{ $customer->email ?: '—' }}
                            </td>

                            <td class="p-4 font-body text-xs" style="color: #9C9690; letter-spacing: 0.04em;">
                                @if($customer->last_visit_at)
                                    {{ $customer->last_visit_at->format('d M Y') }}
                                @else
                                    <span style="color: #BDBDB8;">—</span>
                                @endif
                            </td>

                            {{-- Account status --}}
                            <td class="p-4">
                                @if($customer->user_id)
                                    <span class="font-body text-xs tracking-[0.08em] uppercase px-2.5 py-1 inline-flex items-center gap-1.5" style="background: #EAF1EA; color: #5C7A5C; border-radius: 4px;">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4" fill="#5C7A5C"/><path d="M3 5l1.5 1.5L7 3.5" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Terhubung
                                    </span>
                                @else
                                    <span class="font-body text-xs tracking-[0.08em] uppercase px-2.5 py-1 inline-block" style="background: #EDEAE4; color: #9C9690; border-radius: 4px;">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>

                            {{-- Active status --}}
                            <td class="p-4">
                                @if($customer->is_active)
                                    <span class="font-body text-xs tracking-[0.1em] uppercase px-2.5 py-1 inline-block" style="background: #F0F5F0; color: #5C7A5C; border-radius: 4px;">
                                        Aktif
                                    </span>
                                @else
                                    <span class="font-body text-xs tracking-[0.1em] uppercase px-2.5 py-1 inline-block" style="background: #EDEAE4; color: #9C9690; border-radius: 4px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <a
                                        href="{{ route($customerRoutePrefix . '.bookings.create', ['customer_id' => $customer->id]) }}"
                                        wire:navigate
                                        class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-200"
                                        style="color: #B89B6B; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: #EDEAE4;"
                                        onmouseover="this.style.textDecorationColor='#1C1A18'"
                                        onmouseout="this.style.textDecorationColor='#EDEAE4'"
                                    >
                                        Booking
                                    </a>
                                    <span class="text-[#EDEAE4]">|</span>
                                    <a
                                        href="{{ route($customerRoutePrefix . '.customers.edit', $customer->id) }}"
                                        class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-200"
                                        style="color: #1C1A18; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: #EDEAE4;"
                                        onmouseover="this.style.textDecorationColor='#B89B6B'"
                                        onmouseout="this.style.textDecorationColor='#EDEAE4'"
                                    >
                                        Edit
                                    </a>
                                    <span class="text-[#EDEAE4]">|</span>
                                    <button
                                        type="button"
                                        wire:click="delete('{{ $customer->id }}')"
                                        wire:confirm="Apakah Anda yakin ingin menghapus customer ini beserta seluruh riwayat bookingnya?"
                                        class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-200 hover:text-[#B5402C]"
                                        style="color: #9C9690; background: none; border: none; padding: 0; cursor: pointer; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: #EDEAE4;"
                                        onmouseover="this.style.textDecorationColor='#B5402C'"
                                        onmouseout="this.style.textDecorationColor='#EDEAE4'"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="font-body text-sm text-center" style="padding: 56px 24px; color: #9C9690;">
                                <div style="opacity: 0.4; font-size: 32px; margin-bottom: 10px;">👤</div>
                                Belum ada customer terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 font-body">
        {{ $customers->links() }}
    </div>
</div>
