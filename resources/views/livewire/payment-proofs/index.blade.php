<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">
    @php
        $paymentProofRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
    @endphp

    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Payment Proof Management
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                Kelola bukti pembayaran VELOURE BEAUTY HOUSE
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

    <div class="bg-white border overflow-x-auto" style="border-color: #EDEAE4;">
        <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse: collapse;">
            <thead>
                <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Booking</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Customer</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Amount</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Status</th>
                    <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentProofs as $paymentProof)
                    <tr style="border-bottom: 1px solid #EDEAE4;">
                        <td class="p-4 font-body text-xs tracking-[0.08em]" style="color: #9C9690;">
                            {{ $paymentProof->booking?->booking_number ?? '—' }}
                        </td>
                        <td class="p-4" style="color: #1C1A18;">
                            {{ $paymentProof->booking?->customer?->name ?? '—' }}
                        </td>
                        <td class="p-4" style="color: #1C1A18; font-weight: 500;">
                            Rp {{ number_format($paymentProof->amount) }}
                        </td>
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'approved' => ['bg' => '#EAF1EA', 'text' => '#5C7A5C'],
                                    'pending'  => ['bg' => '#FBF3E8', 'text' => '#B89B6B'],
                                    'rejected' => ['bg' => '#FBEAE7', 'text' => '#B5402C'],
                                ];
                                $statusKey   = strtolower($paymentProof->status);
                                $statusStyle = $statusColors[$statusKey] ?? ['bg' => '#EDEAE4', 'text' => '#3A3733'];
                            @endphp
                            <span
                                class="font-body text-xs tracking-[0.1em] uppercase px-3 py-1.5 inline-block"
                                style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};"
                            >
                                {{ strtoupper($paymentProof->status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <a
                                href="{{ route($paymentProofRoutePrefix . '.payment-proofs.show', $paymentProof) }}"
                                class="font-body text-xs tracking-[0.1em] uppercase transition-colors duration-300"
                                style="color: #1C1A18; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: #EDEAE4;"
                                onmouseover="this.style.textDecorationColor='#B89B6B'"
                                onmouseout="this.style.textDecorationColor='#EDEAE4'"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 font-body text-sm text-center" style="color: #9C9690;">
                            Belum ada bukti pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($paymentProofs, 'links'))
        <div class="mt-6 font-body text-sm" style="color: #9C9690;">
            {{ $paymentProofs->links() }}
        </div>
    @endif
</div>
