<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Payroll</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Payroll Detail</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">{{ $payroll->employee?->full_name }} &middot; {{ $payroll->month }}/{{ $payroll->year }}</p>
        </div>

        <a href="{{ route('owner.payrolls.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

    <div class="grid lg:grid-cols-[1fr_340px] gap-6">
        <section class="bg-white border overflow-hidden" style="border-color:#EDEAE4;">
            <div class="p-6 sm:p-8 border-b" style="border-color:#EDEAE4;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-2" style="color:#B89B6B;">Rincian Payroll</p>
                <h2 class="font-display text-3xl" style="color:#1C1A18;">{{ strtoupper($payroll->status) }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse:collapse;">
                    <thead>
                        <tr style="background:#FAF9F7; border-bottom:1px solid #EDEAE4;">
                            <th class="text-left p-4 text-xs tracking-[0.15em] uppercase" style="color:#9C9690; font-weight:500;">Deskripsi</th>
                            <th class="text-left p-4 text-xs tracking-[0.15em] uppercase" style="color:#9C9690; font-weight:500;">Tipe</th>
                            <th class="text-right p-4 text-xs tracking-[0.15em] uppercase" style="color:#9C9690; font-weight:500;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payroll->details as $detail)
                            <tr style="border-bottom:1px solid #EDEAE4;">
                                <td class="p-4" style="color:#1C1A18;">{{ $detail->description }}</td>
                                <td class="p-4" style="color:#3A3733;">{{ strtoupper($detail->type) }}</td>
                                <td class="p-4 text-right" style="color:#3A3733;">Rp {{ number_format((float) $detail->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center" style="color:#9C9690;">Belum ada rincian payroll.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="bg-white border p-6 h-fit" style="border-color:#EDEAE4;">
            <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color:#B89B6B;">Summary</p>

            <div class="space-y-4 font-body text-sm" style="color:#3A3733;">
                <div>
                    <p class="text-xs tracking-[0.15em] uppercase" style="color:#9C9690;">Karyawan</p>
                    <p class="mt-1">{{ $payroll->employee?->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs tracking-[0.15em] uppercase" style="color:#9C9690;">Gross Salary</p>
                    <p class="mt-1">Rp {{ number_format((float) $payroll->gross_salary, 0, ',', '.') }}</p>
                </div>
                <div class="border-t pt-4" style="border-color:#EDEAE4;">
                    <p class="text-xs tracking-[0.15em] uppercase" style="color:#9C9690;">Net Salary</p>
                    <p class="font-display text-3xl mt-1" style="color:#1C1A18;">Rp {{ number_format((float) $payroll->net_salary, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3">
                @if($payroll->status === 'draft')
                    <button wire:click="approve" type="button" class="font-body text-xs tracking-[0.15em] uppercase px-6 py-3 transition-opacity duration-300 hover:opacity-90" style="background:#B89B6B; color:white;">
                        Approve Payroll
                    </button>
                @endif

                @if($payroll->status === 'approved')
                    <button wire:click="markAsPaid" type="button" class="font-body text-xs tracking-[0.15em] uppercase px-6 py-3 transition-opacity duration-300 hover:opacity-90" style="background:#4E7A57; color:white;">
                        Mark As Paid
                    </button>
                @endif

                <a href="{{ route('owner.payrolls.pdf', $payroll) }}" class="inline-flex justify-center font-body text-xs tracking-[0.15em] uppercase px-6 py-3 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7;">
                    Download Slip PDF
                </a>
            </div>
        </aside>
    </div>
</div>
