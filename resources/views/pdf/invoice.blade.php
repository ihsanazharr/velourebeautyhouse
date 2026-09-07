<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1C1A18;
            font-size: 13px;
            line-height: 1.5;
            padding: 10px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #B89B6B;
            padding-bottom: 20px;
        }
        .header-logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.1em;
            color: #1C1A18;
        }
        .header-subtitle {
            font-size: 10px;
            letter-spacing: 0.2em;
            color: #B89B6B;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9C9690;
            margin-bottom: 5px;
        }
        .meta-value {
            font-size: 13px;
            font-weight: 500;
            color: #1C1A18;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .item-table th {
            background-color: #FAF9F7;
            border-bottom: 1px solid #EDEAE4;
            color: #9C9690;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 12px 10px;
            text-align: left;
            font-weight: 500;
        }
        .item-table td {
            padding: 14px 10px;
            border-bottom: 1px solid #EDEAE4;
            vertical-align: top;
        }
        .item-name {
            font-size: 13px;
            font-weight: bold;
            color: #1C1A18;
        }
        .item-employee {
            font-size: 11px;
            color: #B89B6B;
            margin-top: 2px;
        }
        .item-time {
            font-size: 10px;
            color: #9C9690;
            margin-top: 1px;
        }
        .item-price {
            font-size: 13px;
            font-weight: 600;
            color: #1C1A18;
            text-align: right;
        }
        .totals-table {
            width: 320px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .totals-table td {
            padding: 6px 10px;
            border: none;
        }
        .totals-label {
            font-size: 12px;
            color: #9C9690;
            text-align: right;
        }
        .totals-value {
            font-size: 12px;
            color: #1C1A18;
            font-weight: 600;
            text-align: right;
        }
        .grand-total-label {
            font-size: 14px;
            color: #1C1A18;
            font-weight: bold;
            text-align: right;
            border-top: 1px solid #EDEAE4 !important;
            padding-top: 10px !important;
        }
        .grand-total-value {
            font-size: 16px;
            color: #B89B6B;
            font-weight: bold;
            text-align: right;
            border-top: 1px solid #EDEAE4 !important;
            padding-top: 10px !important;
        }
        .status-badge {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .status-paid {
            background-color: #EAF1EA;
            color: #5C7A5C;
        }
        .status-unpaid {
            background-color: #FBEAE7;
            color: #B5402C;
        }
        .status-partial {
            background-color: #F0EDF8;
            color: #6B5CA5;
        }
        .status-pending {
            background-color: #FBF3E8;
            color: #B89B6B;
        }
    </style>
</head>
<body>

    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="border: none; padding: 0; width: 60%;">
                <div class="header-logo">VELOURÉ</div>
                <div class="header-subtitle">BEAUTY HOUSE</div>
                <div style="font-size: 10px; color: #9C9690; margin-top: 5px; line-height: 1.4;">
                    Jl. Sukamekar III No.2, Sukawarna, Sukajadi,<br>
                    Bandung, Jawa Barat 40164<br>
                    WA: 0882-1889-8999
                </div>
            </td>
            <td style="border: none; padding: 0; width: 40%; text-align: right; vertical-align: top;">
                <div style="font-size: 20px; font-weight: 300; color: #B89B6B; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 8px;">Invoice</div>
                <div class="meta-title">No. Invoice</div>
                <div class="meta-value" style="margin-bottom: 5px;">{{ $invoice->invoice_number }}</div>
                <div class="meta-title">Tanggal Terbit</div>
                <div class="meta-value">{{ $invoice->created_at?->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="meta-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="border: none; padding: 0; width: 50%; vertical-align: top;">
                <div class="meta-title">Diterbitkan Untuk:</div>
                <div class="meta-value" style="font-size: 14px; font-weight: bold; margin-bottom: 2px;">{{ $invoice->customer?->name }}</div>
                @if($invoice->customer?->phone && $invoice->customer?->phone !== '-')
                    <div style="font-size: 11px; color: #9C9690;">Telp: {{ $invoice->customer->phone }}</div>
                @endif
                @if($invoice->customer?->email)
                    <div style="font-size: 11px; color: #9C9690;">Email: {{ $invoice->customer->email }}</div>
                @endif
            </td>
            <td style="border: none; padding: 0; width: 50%; vertical-align: top; text-align: right;">
                @if($invoice->booking)
                    <div class="meta-title">Info Reservasi:</div>
                    <div class="meta-value" style="margin-bottom: 2px;">Booking No: {{ $invoice->booking->booking_number }}</div>
                    <div style="font-size: 11px; color: #3A3733;">Tanggal: {{ $invoice->booking->booking_date?->format('d M Y') }}</div>
                    @if($invoice->booking->booking_start)
                        <div style="font-size: 11px; color: #9C9690;">
                            Waktu: {{ $invoice->booking->booking_start->timezone('Asia/Jakarta')->format('H:i') }} – {{ $invoice->booking->booking_end?->timezone('Asia/Jakarta')->format('H:i') ?? 'Selesai' }} WIB
                        </div>
                    @endif
                @endif
            </td>
        </tr>
    </table>

    <table class="item-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 70%;">Deskripsi Layanan & Produk</th>
                <th style="width: 30%; text-align: right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $booking = $invoice->booking;
                $services = $booking?->services ?? collect();
                $products = $booking?->products ?? collect();
            @endphp
            @foreach($services as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->service?->name ?? 'Layanan Kecantikan' }}</div>
                        @if($item->employee)
                            <div class="item-employee">Stylist: {{ $item->employee->full_name }}</div>
                        @endif
                        @if($item->start_time)
                            <div class="item-time">
                                Jam: {{ $item->start_time->format('H:i') }}{{ $item->end_time ? ' – '.$item->end_time->format('H:i') : '' }}
                            </div>
                        @endif
                    </td>
                    <td class="item-price">
                        Rp {{ number_format((float)$item->price, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            @foreach($products as $pItem)
                <tr>
                    <td>
                        <div class="item-name">{{ $pItem->product?->name ?? 'Produk' }} (x{{ $pItem->quantity }})</div>
                        @if($pItem->product?->sku)
                            <div class="item-time">SKU: {{ $pItem->product->sku }} &middot; @ Rp {{ number_format((float)$pItem->unit_price, 0, ',', '.') }}</div>
                        @else
                            <div class="item-time">@ Rp {{ number_format((float)$pItem->unit_price, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="item-price">
                        Rp {{ number_format((float)$pItem->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            @if($services->isEmpty() && $products->isEmpty())
                <tr>
                    <td colspan="2" style="text-align: center; color: #9C9690; font-style: italic;">
                        Tidak ada detail layanan atau produk
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @php
        $dp = (float) ($booking?->dp_amount ?? 0);
        $subtotal = (float) $invoice->subtotal;
        $discount = (float) $invoice->discount;
        $grandTotal = (float) $invoice->grand_total;
        $remaining = max(0, $grandTotal - $dp);
    @endphp

    <table class="totals-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="totals-label">Subtotal</td>
            <td class="totals-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($dp > 0)
            <tr>
                <td class="totals-label">Uang Muka (DP)</td>
                <td class="totals-value" style="color: #6B5CA5;">-Rp {{ number_format($dp, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if($discount > 0)
            <tr>
                <td class="totals-label">Diskon</td>
                <td class="totals-value" style="color: #B5402C;">-Rp {{ number_format($discount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td class="grand-total-label">Grand Total</td>
            <td class="grand-total-value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
        @if($dp > 0 && $remaining > 0)
            <tr>
                <td class="totals-label" style="font-weight: 500;">Sisa Pembayaran</td>
                <td class="totals-value" style="color: #B5402C; font-size: 13px;">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td class="totals-label" style="padding-top: 15px;">Status Pembayaran</td>
            <td class="totals-value" style="padding-top: 15px;">
                @php
                    $statusClass = match ($invoice->payment_status) {
                        'paid' => 'status-paid',
                        'unpaid' => 'status-unpaid',
                        'partial' => 'status-partial',
                        'pending' => 'status-pending',
                        default => '',
                    };
                    $statusLabel = match ($invoice->payment_status) {
                        'paid' => 'Lunas',
                        'unpaid' => 'Belum Lunas',
                        'partial' => 'DP Paid',
                        'pending' => 'Menunggu Verifikasi',
                        default => strtoupper($invoice->payment_status),
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div style="margin-top: 50px; text-align: center; border-top: 1px dashed #EDEAE4; padding-top: 20px; color: #9C9690; font-size: 10px;">
        Terima kasih atas kunjungan Anda ke VELOURÉ Beauty House.<br>
        Layanan kami dikerjakan dengan presisi untuk kecantikan terbaik Anda.
    </div>

</body>
</html>