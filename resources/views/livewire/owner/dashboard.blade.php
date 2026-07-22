<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="mb-10 flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner Dashboard</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.05em]" style="color: #1C1A18;">
                VELOUR&Eacute; BEAUTY HOUSE
            </h1>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-px mb-px" style="background-color: #EDEAE4;">

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Total Customer
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #1C1A18;">
                {{ $totalCustomers }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Total Employee
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #1C1A18;">
                {{ $totalEmployees }}
            </h2>
        </div>

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
                Produk Hampir Habis
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #B5402C;">
                {{ $lowStockProducts }}
            </h2>
        </div>

    </div>

    <!-- Secondary Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-px mb-10" style="background-color: #EDEAE4;">

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Revenue Bulan Ini
            </p>

            <h2 class="font-display text-3xl mt-3" style="color: #5C7A5C;">
                Rp {{ number_format($monthlyRevenue) }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Payroll Bulan Ini
            </p>

            <h2 class="font-display text-3xl mt-3" style="color: #5C6E8C;">
                Rp {{ number_format($monthlyPayroll) }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Invoice Belum Lunas
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #B89B6B;">
                {{ $unpaidInvoices }}
            </h2>
        </div>

        <div class="bg-white p-6">
            <p class="font-body text-xs tracking-[0.15em] uppercase" style="color: #9C9690;">
                Booking Pending
            </p>

            <h2 class="font-display text-5xl mt-3" style="color: #B5402C;">
                {{ $pendingBookings }}
            </h2>
        </div>

    </div>

    <!-- Ringkasan Sistem -->
    <div class="bg-white border" style="border-color: #EDEAE4;">

        <div class="p-8 border-b" style="border-color: #EDEAE4;">
            <div class="flex items-center gap-3 mb-1">
                <span class="h-px w-6" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #9C9690;">Overview</span>
            </div>
            <h3 class="font-display text-2xl" style="color: #1C1A18;">
                Ringkasan Sistem
            </h3>
        </div>

        <!-- Quick Access -->
        <div class="p-8 border-b" style="border-color: #EDEAE4;">

            <div class="flex items-center gap-3 mb-6">
                <span class="h-px w-6" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #9C9690;">Quick Access</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-px" style="background-color: #EDEAE4;">

                <a
                    href="{{ route('owner.bookings.index') }}"
                    class="bg-white p-5 text-center font-body text-sm tracking-[0.05em] transition-colors duration-300 hover:bg-[#FAF9F7] hover:text-[#B89B6B]"
                    style="color: #3A3733;"
                >
                    Booking
                </a>

                <a
                    href="{{ route('owner.customers.index') }}"
                    class="bg-white p-5 text-center font-body text-sm tracking-[0.05em] transition-colors duration-300 hover:bg-[#FAF9F7] hover:text-[#B89B6B]"
                    style="color: #3A3733;"
                >
                    Customer
                </a>

                <a
                    href="{{ route('owner.payrolls.index') }}"
                    class="bg-white p-5 text-center font-body text-sm tracking-[0.05em] transition-colors duration-300 hover:bg-[#FAF9F7] hover:text-[#B89B6B]"
                    style="color: #3A3733;"
                >
                    Payroll
                </a>

                <a
                    href="{{ route('owner.invoices.index') }}"
                    class="bg-white p-5 text-center font-body text-sm tracking-[0.05em] transition-colors duration-300 hover:bg-[#FAF9F7] hover:text-[#B89B6B]"
                    style="color: #3A3733;"
                >
                    Invoice
                </a>

            </div>

        </div>

        <!-- Feature List -->
        <div class="p-8">
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <li class="flex items-center gap-3 font-body text-sm" style="color: #3A3733;">
                    <span class="h-1 w-1 rounded-full shrink-0" style="background-color: #B89B6B;"></span>
                    Customer Management
                </li>
                <li class="flex items-center gap-3 font-body text-sm" style="color: #3A3733;">
                    <span class="h-1 w-1 rounded-full shrink-0" style="background-color: #B89B6B;"></span>
                    Employee Management
                </li>
                <li class="flex items-center gap-3 font-body text-sm" style="color: #3A3733;">
                    <span class="h-1 w-1 rounded-full shrink-0" style="background-color: #B89B6B;"></span>
                    Booking Management
                </li>
                <li class="flex items-center gap-3 font-body text-sm" style="color: #3A3733;">
                    <span class="h-1 w-1 rounded-full shrink-0" style="background-color: #B89B6B;"></span>
                    Inventory Management
                </li>
                <li class="flex items-center gap-3 font-body text-sm" style="color: #3A3733;">
                    <span class="h-1 w-1 rounded-full shrink-0" style="background-color: #B89B6B;"></span>
                    Attendance &amp; Payroll
                </li>
            </ul>
        </div>

    </div>

</div>