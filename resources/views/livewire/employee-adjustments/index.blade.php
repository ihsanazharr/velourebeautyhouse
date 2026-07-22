<div class="p-6">

    <div
        style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        "
    >

        <h1
            style="
                font-size:28px;
                font-weight:bold;
            "
        >
            Employee Adjustments
        </h1>

        <a
            href="{{ route('owner.adjustments.create') }}"
            style="
                background:black;
                color:white;
                padding:10px 20px;
                border-radius:8px;
                text-decoration:none;
            "
        >
            + Tambah Adjustment
        </a>

    </div>

    @if (session()->has('success'))

        <div
            style="
                background:#dcfce7;
                color:#166534;
                padding:12px;
                border-radius:8px;
                margin-bottom:16px;
            "
        >
            {{ session('success') }}
        </div>

    @endif

    <table
        width="100%"
        border="1"
        cellpadding="10"
        cellspacing="0"
    >

        <thead>

            <tr>

                <th>Tanggal</th>

                <th>Karyawan</th>

                <th>Jenis</th>

                <th>Nominal</th>

                <th>Keterangan</th>

            </tr>

        </thead>

        <tbody>

            @forelse($adjustments as $adjustment)

                <tr>

                    <td>
                        {{ $adjustment->adjustment_date }}
                    </td>

                    <td>
                        {{ $adjustment->employee?->full_name }}
                    </td>

                    <td>
                        {{ strtoupper($adjustment->type) }}
                    </td>

                    <td>
                        Rp {{ number_format($adjustment->amount) }}
                    </td>

                    <td>
                        {{ $adjustment->description }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" align="center">
                        Belum ada data adjustment
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>