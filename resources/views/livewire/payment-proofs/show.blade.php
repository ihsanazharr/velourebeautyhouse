<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    @php
        $paymentProofRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
    @endphp

    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Payment</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Payment Proof Detail</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">{{ $paymentProof->booking?->booking_number ?? '-' }}</p>
        </div>

        <a href="{{ route($paymentProofRoutePrefix . '.payment-proofs.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

    @if(session()->has('success'))
        <div class="font-body text-sm mb-6 px-5 py-4 border-l-2" style="background:white; border-color:#4E7A57; color:#3A3733;">
            {{ session('success') }}
        </div>
    @endif

    @php
        $statusStyle = [
            'pending' => 'background:#FBF3E8; color:#B89B6B;',
            'approved' => 'background:#ECF7EE; color:#4E7A57;',
            'rejected' => 'background:#FBECEA; color:#B5402C;',
        ][$paymentProof->status] ?? 'background:#EDEAE4; color:#3A3733;';
    @endphp

    <div class="grid lg:grid-cols-[1fr_380px] gap-6">
        <section class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
            <div class="flex items-start justify-between gap-4 mb-8">
                <div>
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-2" style="color:#B89B6B;">Informasi Pembayaran</p>
                    <p class="font-display text-3xl" style="color:#1C1A18;">Rp {{ number_format((float) $paymentProof->amount, 0, ',', '.') }}</p>
                </div>
                <span class="font-body text-xs tracking-[0.12em] uppercase px-3 py-2" style="{{ $statusStyle }}">
                    {{ $paymentProof->status }}
                </span>
            </div>

            <div class="grid sm:grid-cols-2 gap-5 font-body text-sm" style="color:#3A3733;">
                <div class="border p-4" style="border-color:#EDEAE4;">
                    <p class="text-xs tracking-[0.15em] uppercase mb-1" style="color:#9C9690;">Booking</p>
                    <p>{{ $paymentProof->booking?->booking_number ?? '-' }}</p>
                </div>

                <div class="border p-4" style="border-color:#EDEAE4;">
                    <p class="text-xs tracking-[0.15em] uppercase mb-1" style="color:#9C9690;">Customer</p>
                    <p>{{ $paymentProof->booking?->customer?->name ?? '-' }}</p>
                </div>

                <div class="border p-4" style="border-color:#EDEAE4;">
                    <p class="text-xs tracking-[0.15em] uppercase mb-1" style="color:#9C9690;">Upload Date</p>
                    <p>{{ $paymentProof->created_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>

                <div class="border p-4" style="border-color:#EDEAE4;">
                    <p class="text-xs tracking-[0.15em] uppercase mb-1" style="color:#9C9690;">Validated At</p>
                    <p>{{ $paymentProof->validated_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>

            @if($paymentProof->status === 'pending')
                <div class="flex flex-wrap gap-3 mt-8">
                    <button wire:click="approve" type="button" class="font-body text-xs tracking-[0.15em] uppercase px-6 py-3 transition-opacity duration-300 hover:opacity-90" style="background:#4E7A57; color:white;">
                        Approve
                    </button>

                    <button wire:click="reject" type="button" class="font-body text-xs tracking-[0.15em] uppercase px-6 py-3 transition-opacity duration-300 hover:opacity-90" style="background:#B5402C; color:white;">
                        Reject
                    </button>
                </div>
            @endif
        </section>

        <aside class="bg-white border p-6 h-fit" style="border-color:#EDEAE4;">
            <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color:#B89B6B;">Bukti Pembayaran</p>

            <a href="{{ asset('storage/' . $paymentProof->proof_image) }}" target="_blank" class="block border p-3 transition-opacity duration-300 hover:opacity-90" style="border-color:#EDEAE4;">
                <img
                    src="{{ asset('storage/' . $paymentProof->proof_image) }}"
                    alt="Bukti pembayaran {{ $paymentProof->booking?->booking_number }}"
                    class="w-full max-h-[520px] object-contain bg-[#FAF9F7]"
                >
            </a>

            <p class="font-body text-xs mt-4 break-all" style="color:#9C9690;">{{ $paymentProof->proof_image }}</p>
        </aside>
    </div>
</div>
