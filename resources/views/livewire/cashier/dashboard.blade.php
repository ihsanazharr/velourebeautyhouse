<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="mb-10">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Kasir Dashboard</span>
        </div>
        <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
            Dashboard Kasir
        </h1>
        <p class="font-body text-sm mt-2" style="color: #9C9690;">
            Operasional Harian Veloure
        </p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-px mb-10" style="background-color: #EDEAE4;">

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Booking Hari Ini
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #1C1A18;">
                {{ $todayBookings }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Total Customer
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #1C1A18;">
                {{ $todayCustomers }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Pendapatan Hari Ini
            </p>

            <h2 class="font-display text-3xl mt-3" style="color: #5C7A5C;">
                Rp {{ number_format($todayRevenue) }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Attendance Hari Ini
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #1C1A18;">
                {{ $todayAttendances }}
            </h2>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="flex items-center gap-3 mb-6">
        <span class="h-px w-6" style="background-color: #B89B6B;"></span>
        <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #9C9690;">Quick Access</span>
    </div>

    <div class="flex flex-wrap gap-px" style="background-color:#EDEAE4;">

        <a
            href="{{ route('kasir.bookings.index') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
            style="
                background:#1C1A18;
                color:#FAF9F7;
                padding:14px 28px;
                text-decoration:none;
            "
        >
            Booking
        </a>

        <a
            href="{{ route('kasir.attendances.index') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
            style="
                background:#5C6E8C;
                color:#FAF9F7;
                padding:14px 28px;
                text-decoration:none;
            "
        >
            Attendance
        </a>

        <a
            href="{{ route('kasir.invoices.index') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
            style="
                background:#5C7A5C;
                color:#FAF9F7;
                padding:14px 28px;
                text-decoration:none;
            "
        >
            Invoice
        </a>

    </div>

</div>