<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">
    @php
        $routePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
        $booking  = $invoice->booking;
        $services = $booking?->services ?? collect();
        $products = $booking?->products ?? collect();
        $dp       = (float) ($booking?->dp_amount ?? 0);
        $subtotal = (float) $invoice->subtotal;
        $discount = (float) $invoice->discount;
        $grandTotal = (float) $invoice->grand_total;

        $statusMap = [
            'paid'    => ['label' => 'LUNAS',       'bg' => '#EAF1EA', 'text' => '#5C7A5C'],
            'unpaid'  => ['label' => 'BELUM BAYAR', 'bg' => '#FBEAE7', 'text' => '#B5402C'],
            'partial' => ['label' => 'DP',          'bg' => '#F0EDF8', 'text' => '#6B5CA5'],
            'pending' => ['label' => 'MENUNGGU',    'bg' => '#FBF3E8', 'text' => '#B89B6B'],
        ];
        $statusStyle = $statusMap[$invoice->payment_status] ?? ['label' => strtoupper($invoice->payment_status), 'bg' => '#EDEAE4', 'text' => '#3A3733'];
    @endphp

    {{-- Success Hero Banner --}}
    <div class="max-w-3xl mx-auto text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 shadow-sm animate-bounce" style="background-color: #EAF1EA; color: #5C7A5C;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="flex items-center justify-center gap-3 mb-2">
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Pembayaran Selesai</span>
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
        </div>
        <h1 class="font-display text-4xl sm:text-5xl tracking-[0.02em] font-semibold text-[#1C1A18] mb-2">
            Transaksi Berhasil!
        </h1>
        <p class="font-body text-sm text-[#9C9690] max-w-md mx-auto">
            Pembayaran untuk invoice <strong class="text-[#1C1A18]">{{ $invoice->invoice_number }}</strong> telah berhasil diproses.
        </p>

        {{-- Quick Action Buttons --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <button
                onclick="window.print()"
                class="font-body text-xs tracking-[0.15em] uppercase px-5 py-3 transition-all duration-200 flex items-center gap-2 shadow-sm hover:opacity-90"
                style="background: #1C1A18; color: #FAF9F7; border-radius: 8px;"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Cetak Struk</span>
            </button>

            <a
                href="{{ route($routePrefix . '.invoices.pdf', $invoice->id) }}"
                target="_blank"
                class="font-body text-xs tracking-[0.15em] uppercase px-5 py-3 transition-all duration-200 flex items-center gap-2 border hover:bg-white"
                style="color: #1C1A18; border-color: #EDEAE4; background: #FAF9F7; border-radius: 8px;"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span>Unduh PDF</span>
            </a>

            <a
                href="{{ route($routePrefix . '.bookings.create') }}"
                wire:navigate
                class="font-body text-xs tracking-[0.15em] uppercase px-5 py-3 transition-all duration-200 flex items-center gap-2 border hover:bg-white"
                style="color: #B89B6B; border-color: #B89B6B; background: white; border-radius: 8px;"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Buat Booking Baru</span>
            </a>

            <a
                href="{{ route($routePrefix . '.bookings.index') }}"
                wire:navigate
                class="font-body text-xs tracking-[0.15em] uppercase px-5 py-3 transition-all duration-200 border hover:bg-white"
                style="color: #9C9690; border-color: #EDEAE4; border-radius: 8px;"
            >
                Daftar Booking
            </a>
        </div>
    </div>

    {{-- Highlight Metrics Row --}}
    <div class="max-w-3xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10 font-body">
        <div class="bg-white p-4 border border-[#EDEAE4] rounded-xl text-center">
            <div class="text-[10px] uppercase tracking-wider text-[#9C9690] mb-1">Total Transaksi</div>
            <div class="font-semibold text-lg text-[#1C1A18]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-4 border border-[#EDEAE4] rounded-xl text-center">
            <div class="text-[10px] uppercase tracking-wider text-[#9C9690] mb-1">Metode Bayar</div>
            <div class="font-semibold text-lg text-[#1C1A18]">{{ $invoice->payment_method ?: 'Cash' }}</div>
        </div>
        <div class="bg-white p-4 border border-[#EDEAE4] rounded-xl text-center">
            <div class="text-[10px] uppercase tracking-wider text-[#9C9690] mb-1">Diterima</div>
            <div class="font-semibold text-lg text-[#5C7A5C]">Rp {{ number_format((float) ($invoice->cash_received ?: $grandTotal), 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-4 border border-[#EDEAE4] rounded-xl text-center">
            <div class="text-[10px] uppercase tracking-wider text-[#9C9690] mb-1">Kembalian</div>
            <div class="font-semibold text-lg text-[#B89B6B]">Rp {{ number_format((float) ($invoice->cash_change ?: 0), 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- ===================== RECEIPT PREVIEW ===================== --}}
    <div
        id="receipt"
        class="bg-white mx-auto shadow-md"
        style="
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            border: 1px solid #EDEAE4;
            font-family: 'Courier New', Courier, monospace;
            overflow: hidden;
        "
    >
        {{-- Header stripe --}}
        <div style="background: #1C1A18; padding: 24px 28px; text-align: center;">
            <div class="font-display" style="color: #FAF9F7; font-size: 24px; letter-spacing: 0.15em;">VELOURÉ</div>
            <div style="color: #B89B6B; font-size: 10px; letter-spacing: 0.3em; margin-top: 4px; font-family: 'Poppins', sans-serif;">BEAUTY HOUSE</div>
            <div style="color: #9C9690; font-size: 10px; margin-top: 10px; font-family: 'Poppins', sans-serif; line-height: 1.4;">
                Jl. Sukamekar III No.2, Sukawarna, Sukajadi,<br>
                Kota Bandung, Jawa Barat 40164<br>
                WA: 0882-1889-8999
            </div>
        </div>

        {{-- Dashed divider --}}
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 16px 0;"></div>
        </div>

        {{-- Invoice Meta --}}
        <div style="padding: 0 24px 4px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #1C1A18;">
                <span style="color: #9C9690; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 9px; letter-spacing: 0.1em;">No. Invoice</span>
                <span style="font-weight: 600; font-size: 11px;">{{ $invoice->invoice_number }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #1C1A18;">
                <span style="color: #9C9690; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 9px; letter-spacing: 0.1em;">Tanggal</span>
                <span>{{ $invoice->created_at?->format('d M Y, H:i') }}</span>
            </div>
            @if($booking)
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #1C1A18;">
                <span style="color: #9C9690; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 9px; letter-spacing: 0.1em;">No. Booking</span>
                <span>{{ $booking->booking_number }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #1C1A18;">
                <span style="color: #9C9690; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 9px; letter-spacing: 0.1em;">Tgl. Booking</span>
                <span>{{ $booking->booking_date?->format('d M Y') }}</span>
            </div>
            @endif
        </div>

        {{-- Customer Info --}}
        @if(($invoice->show_customer_name ?? true) && $invoice->customer && !empty($invoice->customer->name))
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 12px 0;"></div>
        </div>
        <div style="padding: 0 24px 4px;">
            <div style="font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: #9C9690; font-family: 'Poppins', sans-serif; margin-bottom: 8px;">Customer</div>
            <div style="font-size: 13px; font-weight: 600; color: #1C1A18; margin-bottom: 2px;">{{ $invoice->customer->name }}</div>
            @if($invoice->customer->phone)
            <div style="font-size: 11px; color: #9C9690;">{{ $invoice->customer->phone }}</div>
            @endif
        </div>
        @endif

        {{-- Service Items --}}
        @if($services->isNotEmpty())
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 16px 0;"></div>
        </div>

        <div style="padding: 0 24px;">
            <div style="font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: #9C9690; font-family: 'Poppins', sans-serif; margin-bottom: 12px;">Layanan</div>

            @foreach($services as $item)
                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #1C1A18; font-weight: 500; line-height: 1.4;">
                                {{ $item->service?->name ?? '(Layanan)' }}
                                @if($item->quantity > 1)
                                    <span style="color: #9C9690;"> ×{{ $item->quantity }}</span>
                                @endif
                            </div>
                            @if($item->employee)
                                <div style="font-size: 10px; color: #B89B6B; margin-top: 1px;">
                                    {{ $item->employee->full_name }}
                                </div>
                            @endif
                        </div>
                        <div style="font-size: 12px; color: #1C1A18; white-space: nowrap; font-weight: 600;">
                            Rp {{ number_format((float)$item->price * max(1, $item->quantity ?? 1), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Product Items --}}
        @if($products->isNotEmpty())
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 16px 0;"></div>
        </div>

        <div style="padding: 0 24px;">
            <div style="font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: #9C9690; font-family: 'Poppins', sans-serif; margin-bottom: 12px;">Produk</div>

            @foreach($products as $pItem)
                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #1C1A18; font-weight: 500; line-height: 1.4;">
                                {{ $pItem->product?->name ?? 'Produk' }}
                                <span style="color: #9C9690;"> ×{{ $pItem->quantity }}</span>
                            </div>
                            @if($pItem->product?->sku)
                                <div style="font-size: 10px; color: #9C9690; margin-top: 1px;">
                                    SKU: {{ $pItem->product->sku }}
                                </div>
                            @endif
                        </div>
                        <div style="font-size: 12px; color: #1C1A18; white-space: nowrap; font-weight: 600;">
                            Rp {{ number_format((float)$pItem->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Totals --}}
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 16px 0;"></div>
        </div>

        <div style="padding: 0 24px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #3A3733;">
                <span style="color: #9C9690;">Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            @if($discount > 0)
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #B5402C;">
                <span>Diskon</span>
                <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
            </div>
            @endif

            @if($dp > 0)
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #5C7A5C;">
                <span>DP / Uang Muka</span>
                <span>Rp {{ number_format($dp, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        {{-- Grand Total --}}
        <div style="padding: 0 20px;">
            <div style="border-top: 2px solid #1C1A18; margin: 8px 0 0;"></div>
        </div>
        <div style="padding: 10px 24px 20px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #1C1A18; letter-spacing: 0.05em; font-family: 'Poppins', sans-serif; text-transform: uppercase;">TOTAL</span>
            <span style="font-size: 20px; font-weight: 700; color: #1C1A18; font-family: 'Poppins', sans-serif;">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </span>
        </div>

        {{-- Payment Details on Receipt --}}
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 8px 0;"></div>
        </div>
        <div style="padding: 0 24px 16px; font-size: 11px; color: #3A3733;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span style="color: #9C9690;">Metode Pembayaran</span>
                <span style="font-weight: 600;">{{ $invoice->payment_method ?: 'Cash' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span style="color: #9C9690;">Uang Diterima</span>
                <span>Rp {{ number_format((float)($invoice->cash_received ?: $grandTotal), 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #9C9690;">Kembalian</span>
                <span style="font-weight: 600; color: #5C7A5C;">Rp {{ number_format((float)($invoice->cash_change ?: 0), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Status badge --}}
        <div style="padding: 10px 24px 20px; text-align: center;">
            <span style="
                display: inline-block;
                padding: 8px 28px;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.2em;
                font-family: 'Poppins', sans-serif;
                border-radius: 4px;
                background: {{ $statusStyle['bg'] }};
                color: {{ $statusStyle['text'] }};
            ">
                ★ {{ $statusStyle['label'] }} ★
            </span>
        </div>

        {{-- Footer --}}
        <div style="padding: 0 20px;">
            <div style="border-top: 1px dashed #EDEAE4; margin: 4px 0 16px;"></div>
        </div>
        <div style="text-align: center; padding: 0 24px 28px;">
            <div style="font-size: 10px; color: #9C9690; font-family: 'Poppins', sans-serif; line-height: 1.7;">
                Terima kasih telah mempercayai<br>
                <strong style="color: #B89B6B;">VELOURÉ Beauty House</strong><br>
                untuk perawatan Anda 💛
            </div>
            <div style="font-size: 9px; color: #BDBDB8; margin-top: 10px; font-family: 'Poppins', sans-serif;">
                {{ $invoice->created_at?->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>

    {{-- Print styles --}}
    <style>
        @media print {
            body * { visibility: hidden; }
            #receipt, #receipt * { visibility: visible; }
            #receipt {
                position: fixed;
                left: 50%;
                top: 0;
                transform: translateX(-50%);
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page { size: 80mm auto; margin: 0; }
        }
    </style>
</div>
