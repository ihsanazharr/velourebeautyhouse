<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="flex justify-between items-center flex-wrap gap-4 mb-8">

        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Booking Management
            </h1>
        </div>

        <a
            href="{{ route('owner.bookings.create') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
            style="
                background:#1C1A18;
                color:#FAF9F7;
                padding:14px 26px;
                text-decoration:none;
            "
        >
            + Tambah Booking
        </a>

    </div>

    <input
        type="text"
        wire:model.live="search"
        placeholder="Cari nomor booking..."
        class="font-body text-sm w-full mb-8 focus:outline-none"
        style="
            border:1px solid #EDEAE4;
            border-radius:0;
            padding:13px 16px;
            color:#1C1A18;
            background:white;
        "
    >

    @if(session()->has('success'))

    <div
        class="font-body text-sm mb-6 px-5 py-4 border-l-2"
        style="background:white; border-color:#5C7A5C; color:#3A3733;"
    >
        {{ session('success') }}
    </div>

    @endif

    @if(session()->has('error'))

        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background:white; border-color:#B5402C; color:#3A3733;"
        >
            {{ session('error') }}
        </div>

    @endif

    <div class="bg-white border overflow-x-auto" style="border-color:#EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse:collapse;">

            <thead>

                <tr style="background:#FAF9F7; border-bottom:1px solid #EDEAE4;">

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">No Booking</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Customer</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Tanggal</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Total</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Status</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($bookings as $booking)

                    <tr style="border-bottom:1px solid #EDEAE4;">

                        <td class="p-4" style="color:#1C1A18; font-weight:500;">
                            {{ $booking->booking_number }}
                        </td>

                        <td class="p-4" style="color:#3A3733;">
                            {{ $booking->customer?->name }}
                        </td>

                        <td class="p-4" style="color:#3A3733;">
                            {{ $booking->booking_date }}
                        </td>

                        <td class="p-4" style="color:#3A3733;">
                            Rp {{ number_format($booking->total_amount) }}
                        </td>

                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'completed' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C'],
                                    'confirmed' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C'],
                                    'pending' => ['bg' => '#FBF3E8', 'text' => '#B89B6B'],
                                    'cancelled' => ['bg' => '#FBEAE7', 'text' => '#B5402C'],
                                ];
                                $statusKey = strtolower($booking->status);
                                $statusStyle = $statusColors[$statusKey] ?? ['bg' => '#EDEAE4', 'text' => '#3A3733'];
                            @endphp
                            <span
                                class="font-body text-xs tracking-[0.1em] uppercase px-3 py-1.5 inline-block"
                                style="background:{{ $statusStyle['bg'] }}; color:{{ $statusStyle['text'] }};"
                            >
                                {{ $booking->status }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-3 font-body text-xs tracking-[0.05em]">
                                <a
                                    href="{{ route(
                                        'owner.bookings.edit',
                                        $booking->id
                                    ) }}"
                                    style="color:#1C1A18; text-decoration:underline; text-underline-offset:3px; text-decoration-color:#EDEAE4;"
                                    onmouseover="this.style.textDecorationColor='#B89B6B'"
                                    onmouseout="this.style.textDecorationColor='#EDEAE4'"
                                >
                                    Edit
                                </a>

                                <span style="color:#EDEAE4;">|</span>

                                @if(!$booking->invoice)

                                    <button
                                        wire:click="
                                            generateInvoice(
                                                '{{ $booking->id }}'
                                            )
                                        "
                                        class="uppercase tracking-[0.1em]"
                                        style="color:#B89B6B; background:none; border:none; padding:0; cursor:pointer;"
                                    >
                                        Generate Invoice
                                    </button>

                                @else

                                    <span style="color:#9C9690;">Invoice Sudah Ada</span>

                                @endif
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            align="center"
                            class="p-8 font-body text-sm"
                            style="color:#9C9690;"
                        >
                            Belum ada booking
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <div class="mt-6 font-body">
        {{ $bookings->links() }}
    </div>

</div>