<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Schedule</span>
        </div>
        <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
            Booking Calendar
        </h1>
    </div>

    <div class="bg-white border p-6 mb-8 flex items-center gap-4 flex-wrap" style="border-color:#EDEAE4;">

        <label class="font-body text-xs tracking-[0.15em] uppercase" style="color:#3A3733;">
            Pilih Tanggal
        </label>

        <input
            type="date"
            wire:model.live="date"
            class="font-body text-sm focus:outline-none"
            style="
                border:1px solid #EDEAE4;
                border-radius:0;
                padding:10px 14px;
                color:#1C1A18;
                background:white;
            "
        >

    </div>

    <div class="bg-white border overflow-x-auto" style="border-color:#EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse:collapse;">

            <thead>

                <tr style="background:#FAF9F7; border-bottom:1px solid #EDEAE4;">

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Jam</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Customer</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Layanan</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Stylist</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Durasi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($bookings as $booking)

                    <tr style="border-bottom:1px solid #EDEAE4;">

                        <td class="p-4" style="color:#1C1A18; font-weight:500;">

                            {{
                                $booking
                                    ->start_time
                                    ->format('H:i')
                            }}

                            &ndash;

                            {{
                                $booking
                                    ->end_time
                                    ->format('H:i')
                            }}

                        </td>

                        <td class="p-4" style="color:#3A3733;">

                            {{
                                $booking
                                    ->booking
                                    ->customer
                                    ?->name
                            }}

                        </td>

                        <td class="p-4" style="color:#3A3733;">

                            {{
                                $booking
                                    ->service
                                    ?->name
                            }}

                        </td>

                        <td class="p-4" style="color:#3A3733;">

                            {{
                                $booking
                                    ->employee
                                    ?->full_name
                            }}

                        </td>

                        <td class="p-4">
                            <span
                                class="font-body text-xs tracking-[0.1em] uppercase px-3 py-1.5 inline-block"
                                style="background:#FBF3E8; color:#B89B6B;"
                            >
                                {{
                                    $booking
                                        ->duration_minutes
                                }}
                                menit
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
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

</div>