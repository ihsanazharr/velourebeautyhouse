<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">

        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Payroll Management
            </h1>
        </div>

        <a
            href="{{ route('owner.payrolls.generate') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
            style="
                background:#1C1A18;
                color:#FAF9F7;
                padding:14px 26px;
                text-decoration:none;
            "
        >
            + Generate Payroll
        </a>

    </div>

    @if(session()->has('success'))

        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background:white; border-color:#5C7A5C; color:#3A3733;"
        >
            {{ session('success') }}
        </div>

    @endif

    <div class="bg-white border overflow-x-auto" style="border-color:#EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse:collapse;">

            <thead>

                <tr style="background:#FAF9F7; border-bottom:1px solid #EDEAE4;">

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Karyawan</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Periode</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Gross Salary</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Net Salary</th>

                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color:#9C9690; font-weight:500;">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($payrolls as $payroll)

                    <tr style="border-bottom:1px solid #EDEAE4;">

                        <td class="p-4">

                            <a
                                href="{{
                                    route(
                                        'owner.payrolls.show',
                                        $payroll
                                    )
                                }}"
                                style="color:#1C1A18; text-decoration:underline; text-underline-offset:3px; text-decoration-color:#EDEAE4;"
                                onmouseover="this.style.textDecorationColor='#B89B6B'"
                                onmouseout="this.style.textDecorationColor='#EDEAE4'"
                            >
                                {{ $payroll->employee?->full_name }}
                            </a>

                        </td>

                        <td class="p-4" style="color:#3A3733;">
                            {{ $payroll->month }}/{{ $payroll->year }}
                        </td>

                        <td class="p-4" style="color:#3A3733;">
                            Rp {{ number_format($payroll->gross_salary) }}
                        </td>

                        <td class="p-4" style="color:#1C1A18; font-weight:500;">
                            Rp {{ number_format($payroll->net_salary) }}
                        </td>

                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'paid' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C'],
                                    'pending' => ['bg' => '#FBF3E8', 'text' => '#B89B6B'],
                                    'unpaid' => ['bg' => '#FBEAE7', 'text' => '#B5402C'],
                                ];
                                $statusKey = strtolower($payroll->status);
                                $statusStyle = $statusColors[$statusKey] ?? ['bg' => '#EDEAE4', 'text' => '#3A3733'];
                            @endphp
                            <span
                                class="font-body text-xs tracking-[0.1em] uppercase px-3 py-1.5 inline-block"
                                style="background:{{ $statusStyle['bg'] }}; color:{{ $statusStyle['text'] }};"
                            >
                                {{ strtoupper($payroll->status) }}
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
                            Belum ada payroll
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>