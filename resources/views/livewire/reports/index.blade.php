<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Reports
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                Laporan keuangan & operasional VELOURE BEAUTY HOUSE
            </p>
        </div>
    </div>

    @if(session()->has('success'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background: white; border-color: #5C7A5C; color: #3A3733;"
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- ── KPI Cards (data dari mount()) ──────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Pendapatan Bulan Ini</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                Rp {{ number_format($monthlyRevenue) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Booking Bulan Ini</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                {{ number_format($monthlyBookings) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Gross Profit</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                Rp {{ number_format($grossProfit) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: {{ $unpaidInvoices > 0 ? '#B5402C' : '#EDEAE4' }};">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Invoice Belum Lunas</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: {{ $unpaidInvoices > 0 ? '#B5402C' : '#1C1A18' }};">
                {{ number_format($unpaidInvoices) }}
            </p>
        </div>
    </div>

    {{-- ── Top Lists + Low Stock ────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

        {{-- Top Services --}}
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-4" style="color: #9C9690;">Top Layanan</p>
            @forelse($topServices as $item)
                <div class="flex justify-between items-center py-2" style="border-bottom: 1px solid #EDEAE4;">
                    <span class="font-body text-sm" style="color: #1C1A18;">{{ $item['name'] }}</span>
                    <span class="font-body text-xs tracking-[0.08em]" style="color: #B89B6B;">{{ $item['total'] }}×</span>
                </div>
            @empty
                <p class="font-body text-sm" style="color: #9C9690;">Belum ada data</p>
            @endforelse
        </div>

        {{-- Top Employees --}}
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-4" style="color: #9C9690;">Top Karyawan</p>
            @forelse($topEmployees as $item)
                <div class="flex justify-between items-center py-2" style="border-bottom: 1px solid #EDEAE4;">
                    <span class="font-body text-sm" style="color: #1C1A18;">{{ $item['full_name'] }}</span>
                    <span class="font-body text-xs tracking-[0.08em]" style="color: #B89B6B;">{{ $item['total'] }}×</span>
                </div>
            @empty
                <p class="font-body text-sm" style="color: #9C9690;">Belum ada data</p>
            @endforelse
        </div>

        {{-- Low Stock Products --}}
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-4" style="color: #9C9690;">Stok Menipis</p>
            @forelse($lowStockProducts as $item)
                <div class="flex justify-between items-center py-2" style="border-bottom: 1px solid #EDEAE4;">
                    <span class="font-body text-sm" style="color: #1C1A18;">{{ $item['name'] }}</span>
                    <span
                        class="font-body text-xs tracking-[0.08em] px-2 py-0.5"
                        style="background: #FBEAE7; color: #B5402C;"
                    >
                        {{ $item['current_stock'] }} / {{ $item['alert_stock'] }}
                    </span>
                </div>
            @empty
                <p class="font-body text-sm" style="color: #5C7A5C;">Semua stok aman</p>
            @endforelse
        </div>

    </div>

    {{-- ── Filter Period ────────────────────────────────────── --}}
    <div class="bg-white border mb-4 p-5 flex flex-wrap gap-6 items-end" style="border-color: #EDEAE4;">
        <div class="flex flex-col gap-1">
            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Dari Tanggal</label>
            <input
                type="date"
                wire:model.live="dateFrom"
                class="font-body text-sm outline-none bg-transparent py-1 border-b"
                style="border-color: #EDEAE4; color: #1C1A18; min-width: 160px;"
            >
        </div>
        <div class="flex flex-col gap-1">
            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Sampai Tanggal</label>
            <input
                type="date"
                wire:model.live="dateTo"
                class="font-body text-sm outline-none bg-transparent py-1 border-b"
                style="border-color: #EDEAE4; color: #1C1A18; min-width: 160px;"
            >
        </div>
    </div>

    {{-- ── Summary Cards (berdasar filter tanggal) ─────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Pendapatan (Filter)</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                Rp {{ number_format($summary['total_revenue'] ?? 0) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Total Transaksi</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                {{ number_format($summary['total_transactions'] ?? 0) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Total Payroll</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                Rp {{ number_format($summary['total_payroll'] ?? 0) }}
            </p>
        </div>
        <div class="bg-white border p-5" style="border-color: #EDEAE4;">
            <p class="font-body text-xs tracking-[0.1em] uppercase mb-3" style="color: #9C9690;">Customer Aktif</p>
            <p class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">
                {{ number_format($summary['active_customers'] ?? 0) }}
            </p>
        </div>
    </div>

    {{-- ── Invoice Table (berdasar filter tanggal) ─────────── --}}
    <div class="bg-white border overflow-x-auto" style="border-color: #EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse: collapse;">
            <thead>
                <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Tanggal</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Invoice</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Customer</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Booking</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Total</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr style="border-bottom: 1px solid #EDEAE4;">
                        <td class="p-4 font-body text-xs" style="color: #9C9690; letter-spacing: 0.04em;">
                            {{ $report->created_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="p-4 font-body text-xs tracking-[0.08em]" style="color: #9C9690;">
                            {{ $report->invoice_number ?? '—' }}
                        </td>
                        <td class="p-4" style="color: #1C1A18;">
                            {{ $report->customer?->name ?? '—' }}
                        </td>
                        <td class="p-4 font-body text-xs tracking-[0.08em]" style="color: #9C9690;">
                            {{ $report->booking?->booking_number ?? '—' }}
                        </td>
                        <td class="p-4" style="color: #1C1A18; font-weight: 500;">
                            Rp {{ number_format($report->grand_total ?? 0) }}
                        </td>
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'paid'    => ['bg' => '#EAF1EA', 'text' => '#5C7A5C'],
                                    'pending' => ['bg' => '#FBF3E8', 'text' => '#B89B6B'],
                                    'unpaid'  => ['bg' => '#FBEAE7', 'text' => '#B5402C'],
                                    'partial' => ['bg' => '#F0EDF8', 'text' => '#6B5CA5'],
                                ];
                                $statusKey   = strtolower($report->payment_status ?? '');
                                $statusStyle = $statusColors[$statusKey] ?? ['bg' => '#EDEAE4', 'text' => '#3A3733'];
                            @endphp
                            <span
                                class="font-body text-xs tracking-[0.1em] uppercase px-3 py-1.5 inline-block"
                                style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};"
                            >
                                {{ strtoupper($report->payment_status ?? '—') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 font-body text-sm text-center" style="color: #9C9690;">
                            Belum ada data invoice untuk periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 font-body text-sm" style="color: #9C9690;">
        {{ $reports->links() }}
    </div>

</div>