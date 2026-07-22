<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">
    @php
        $invoiceRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
        $booking  = $invoice->booking;
        $services = $booking?->services ?? collect();
        $proofs   = $booking?->paymentProofs ?? collect();
        $dp       = (float) ($booking?->dp_amount ?? 0);
        $subtotal = (float) $invoice->subtotal;
        $discount = (float) $invoice->discount;
        $grandTotal = (float) $invoice->grand_total;
        $remaining  = max(0, $grandTotal - $dp);

        $isPaid = $invoice->payment_status === 'paid';

        $statusMap = [
            'paid'    => ['label' => 'LUNAS',    'bg' => '#EAF1EA', 'text' => '#5C7A5C'],
            'unpaid'  => ['label' => 'BELUM BAYAR', 'bg' => '#FBEAE7', 'text' => '#B5402C'],
            'partial' => ['label' => 'DP',       'bg' => '#F0EDF8', 'text' => '#6B5CA5'],
            'pending' => ['label' => 'MENUNGGU',  'bg' => '#FBF3E8', 'text' => '#B89B6B'],
        ];
        $statusStyle = $statusMap[$invoice->payment_status] ?? ['label' => strtoupper($invoice->payment_status), 'bg' => '#EDEAE4', 'text' => '#3A3733'];
    @endphp

    {{-- Page header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Invoice</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Detail Invoice</h1>
        </div>
        <div class="flex items-center gap-4">
            <a
                href="{{ route($invoiceRoutePrefix . '.invoices.index') }}"
                wire:navigate
                class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4"
                style="color: #9C9690; text-decoration-color: #EDEAE4;"
                onmouseover="this.style.textDecorationColor='#B89B6B'"
                onmouseout="this.style.textDecorationColor='#EDEAE4'"
            >
                ← Kembali
            </a>
            <button
                onclick="window.print()"
                class="font-body text-xs tracking-[0.15em] uppercase px-5 py-2.5 transition-all duration-200"
                style="background: #1C1A18; color: #FAF9F7; border: none; border-radius: 8px; cursor: pointer;"
                onmouseover="this.style.background='#3A3733'"
                onmouseout="this.style.background='#1C1A18'"
            >
                🖨 Cetak Struk
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ===================== RECEIPT / STRUK ===================== --}}
        <div
            id="receipt"
            class="bg-white mx-auto"
            style="
                width: 100%;
                max-width: 400px;
                border-radius: 12px;
                border: 1px solid #EDEAE4;
                font-family: 'Courier New', Courier, monospace;
                overflow: hidden;
            "
        >
            {{-- Header stripe --}}
            <div style="background: #1C1A18; padding: 24px 28px; text-align: center;">
                <div class="font-display" style="color: #FAF9F7; font-size: 22px; letter-spacing: 0.15em;">VELOURÉ</div>
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
                @if($booking->booking_start)
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; color: #1C1A18;">
                    <span style="color: #9C9690; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 9px; letter-spacing: 0.1em;">Waktu</span>
                    <span>{{ $booking->booking_start->timezone('Asia/Jakarta')->format('H:i') }} — {{ $booking->booking_end?->timezone('Asia/Jakarta')->format('H:i') ?? '...' }}</span>
                </div>
                @endif
                @endif
            </div>

            {{-- Customer Info --}}
            @if($invoice->customer)
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
            <div style="padding: 0 20px;">
                <div style="border-top: 1px dashed #EDEAE4; margin: 16px 0;"></div>
            </div>

            <div style="padding: 0 24px;">
                <div style="font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: #9C9690; font-family: 'Poppins', sans-serif; margin-bottom: 12px;">Layanan</div>

                @forelse($services as $item)
                    <div style="margin-bottom: 12px;">
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
                                @if($item->start_time)
                                    <div style="font-size: 10px; color: #9C9690;">
                                        {{ $item->start_time->format('H:i') }}{{ $item->end_time ? ' – '.$item->end_time->format('H:i') : '' }}
                                    </div>
                                @endif
                            </div>
                            <div style="font-size: 12px; color: #1C1A18; white-space: nowrap; font-weight: 600;">
                                Rp {{ number_format((float)$item->price * max(1, $item->quantity ?? 1), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="font-size: 11px; color: #9C9690; text-align: center; padding: 8px 0;">Tidak ada detail layanan</div>
                @endforelse
            </div>

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

            @if($dp > 0 && !$isPaid)
            <div style="padding: 0 24px 16px; display: flex; justify-content: space-between;">
                <span style="font-size: 11px; color: #B5402C;">Sisa yang harus dibayar</span>
                <span style="font-size: 13px; font-weight: 700; color: #B5402C;">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
            </div>
            @endif

            {{-- Payment Details on Receipt --}}
            @if($isPaid && $invoice->payment_method)
            <div style="padding: 0 20px;">
                <div style="border-top: 1px dashed #EDEAE4; margin: 8px 0;"></div>
            </div>
            <div style="padding: 0 24px 16px; font-size: 11px; color: #3A3733;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: #9C9690;">Metode Pembayaran</span>
                    <span style="font-weight: 600;">{{ $invoice->payment_method }}</span>
                </div>
                @if($invoice->payment_method === 'Cash')
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: #9C9690;">Uang Diterima</span>
                    <span>Rp {{ number_format((float)$invoice->cash_received, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #9C9690;">Kembalian</span>
                    <span>Rp {{ number_format((float)$invoice->cash_change, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif

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

            {{-- Payment Proofs --}}
            @if($proofs->count() > 0)
            <div style="padding: 0 20px;">
                <div style="border-top: 1px dashed #EDEAE4; margin: 4px 0 16px;"></div>
            </div>
            <div style="padding: 0 24px 16px;">
                <div style="font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: #9C9690; font-family: 'Poppins', sans-serif; margin-bottom: 10px;">Bukti Pembayaran</div>
                @foreach($proofs as $proof)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 11px;">
                        <span style="color: #3A3733;">
                            {{ $proof->created_at?->format('d M Y H:i') }}
                            @if($proof->payment_method)
                                · {{ $proof->payment_method }}
                            @endif
                        </span>
                        @php
                            $proofStatus = match($proof->status) {
                                'approved' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C', 'label' => '✓ Terverifikasi'],
                                'rejected' => ['bg' => '#FBEAE7', 'text' => '#B5402C', 'label' => '✗ Ditolak'],
                                default    => ['bg' => '#FBF3E8', 'text' => '#B89B6B', 'label' => '⏳ Menunggu'],
                            };
                        @endphp
                        <span style="font-size: 9px; padding: 2px 8px; border-radius: 3px; font-family: 'Poppins', sans-serif; background: {{ $proofStatus['bg'] }}; color: {{ $proofStatus['text'] }};">
                            {{ $proofStatus['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

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

        {{-- ===================== SIDEBAR INFO ===================== --}}
        <div class="flex-1 min-w-0 space-y-5 w-full">

            {{-- Status Card / Checkout --}}
            <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">Status Pembayaran</p>
                <div class="flex items-center gap-4 mb-4">
                    <span
                        class="font-body text-xs tracking-[0.12em] uppercase px-4 py-2 inline-block"
                        style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}; border-radius: 6px; font-weight: 600;"
                    >
                        {{ $statusStyle['label'] }}
                    </span>
                    <div>
                        <p class="font-body text-xs" style="color: #9C9690;">Booking: {{ strtoupper($booking?->status ?? '—') }}</p>
                    </div>
                </div>

                @if($isPaid)
                    <div style="border-top: 1px solid #EDEAE4;" class="pt-4 space-y-2.5 font-body text-xs">
                        <div class="flex justify-between">
                            <span style="color: #9C9690;">Metode Pembayaran</span>
                            <span class="font-semibold text-[#1C1A18]">{{ $invoice->payment_method ?: 'QRIS' }}</span>
                        </div>
                        @if($invoice->payment_method === 'Cash')
                            <div class="flex justify-between">
                                <span style="color: #9C9690;">Uang Diterima</span>
                                <span class="text-[#3A3733]">Rp {{ number_format((float)$invoice->cash_received, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span style="color: #9C9690;">Kembalian</span>
                                <span class="text-[#5C7A5C] font-semibold">Rp {{ number_format((float)$invoice->cash_change, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @elseif(auth()->user()->hasRole('owner') || auth()->user()->hasRole('kasir'))
                    {{-- Cashier Checkout Form --}}
                    <form wire:submit="markPaid" style="border-top: 1px solid #EDEAE4;" class="pt-4 mt-4 space-y-4">
                        <div>
                            <label class="block font-body text-[10px] tracking-[0.15em] uppercase mb-1.5" style="color: #9C9690;">Metode Pembayaran</label>
                            <select 
                                wire:model.live="payment_method"
                                class="font-body text-xs w-full focus:outline-none"
                                style="border: 1px solid #EDEAE4; border-radius: 6px; padding: 10px; background: #FAF9F7;"
                            >
                                <option value="Cash">Cash / Tunai</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Debit">Debit Card</option>
                            </select>
                        </div>

                        @if($payment_method === 'Cash')
                            <div>
                                <label class="block font-body text-[10px] tracking-[0.15em] uppercase mb-1.5" style="color: #9C9690;">Uang Diterima (Rp)</label>
                                <input 
                                    type="number" 
                                    min="0" 
                                    wire:model.live="cash_received"
                                    class="font-body text-xs w-full focus:outline-none"
                                    style="border: 1px solid #EDEAE4; border-radius: 6px; padding: 10px; background: #FAF9F7;"
                                    placeholder="Jumlah cash diterima"
                                >
                                <x-input-error :messages="$errors->get('cash_received')" class="mt-1" />
                            </div>

                            <div class="flex justify-between items-center bg-[#F0F5F0] p-2.5 rounded-lg text-xs font-body">
                                <span style="color: #5C7A5C;">Uang Kembali:</span>
                                <span class="font-semibold" style="color: #5C7A5C;">Rp {{ number_format($cash_change, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button
                            type="submit"
                            class="w-full font-body text-xs tracking-[0.15em] uppercase py-3 transition-all duration-200"
                            style="background: #5C7A5C; color: white; border: none; border-radius: 8px; cursor: pointer;"
                            onmouseover="this.style.opacity='0.88'"
                            onmouseout="this.style.opacity='1'"
                        >
                            ✓ Proses Pembayaran
                        </button>
                    </form>
                @endif
            </div>

            {{-- Summary Card --}}
            <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Ringkasan</p>
                <div class="space-y-3 font-body text-sm">
                    <div class="flex justify-between">
                        <span style="color: #9C9690;">Subtotal</span>
                        <span style="color: #1C1A18;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between">
                        <span style="color: #9C9690;">Diskon</span>
                        <span style="color: #B5402C;">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($dp > 0)
                    <div class="flex justify-between">
                        <span style="color: #9C9690;">DP / Uang Muka</span>
                        <span style="color: #5C7A5C;">Rp {{ number_format($dp, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-3" style="border-top: 2px solid #1C1A18;">
                        <span class="font-body font-semibold" style="color: #1C1A18;">Grand Total</span>
                        <span class="font-display text-2xl" style="color: #1C1A18;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    @if($dp > 0 && !$isPaid)
                    <div class="flex justify-between">
                        <span style="color: #B5402C;">Sisa Tagihan</span>
                        <span style="color: #B5402C; font-weight: 600;">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Booking Info Card --}}
            @if($booking)
            <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 24px;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">Info Booking</p>
                <div class="space-y-3 font-body text-sm">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.12em] mb-1" style="color: #9C9690;">No. Booking</p>
                        <p style="color: #1C1A18; font-weight: 500;">{{ $booking->booking_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.12em] mb-1" style="color: #9C9690;">Tanggal</p>
                        <p style="color: #3A3733;">{{ $booking->booking_date?->format('d M Y') }}</p>
                    </div>
                    @if($booking->booking_start)
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.12em] mb-1" style="color: #9C9690;">Waktu</p>
                        <p style="color: #3A3733;">{{ $booking->booking_start->timezone('Asia/Jakarta')->format('H:i') }} – {{ $booking->booking_end?->timezone('Asia/Jakarta')->format('H:i') ?? '...' }}</p>
                    </div>
                    @endif
                    @if($booking->notes)
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.12em] mb-1" style="color: #9C9690;">Catatan</p>
                        <p style="color: #9C9690; font-size: 12px;">{{ $booking->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

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
