<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    @php
        $bookingRoutePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
    @endphp

    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Booking</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Edit Booking</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">{{ $booking->booking_number }} &middot; {{ $booking->booking_date?->format('d M Y') }}</p>
        </div>

        <a href="{{ route($bookingRoutePrefix . '.bookings.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

    <style>
        .veloure-form :where(select) {
            border-radius: 0;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: white;
            width: 100%;
        }
        .veloure-form :where(select):focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 1px #B89B6B;
        }
    </style>

    <form wire:submit="save" class="veloure-form max-w-5xl">
        <div class="grid lg:grid-cols-[1fr_320px] gap-6">
            <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color:#B89B6B;">Detail Booking</p>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Customer</label>
                        <select wire:model="customer_id">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Layanan</label>
                        <select wire:model="service_id">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Stylist</label>
                        <select wire:model="employee_id">
                            <option value="">Pilih stylist</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Status</label>
                        <select wire:model="status">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No Show</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
            </div>

            <aside class="bg-white border p-6 h-fit" style="border-color:#EDEAE4;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color:#B89B6B;">Ringkasan</p>
                <div class="space-y-4 font-body text-sm" style="color:#3A3733;">
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Jadwal</p>
                        <p class="mt-1">{{ $booking->booking_start?->format('d M Y H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Total</p>
                        <p class="mt-1">Rp {{ number_format((float) $booking->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Catatan</p>
                        <p class="mt-1 whitespace-pre-line">{{ $booking->notes ?: '-' }}</p>
                    </div>
                    @if($booking->invoice)
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Invoice</p>
                        <p class="mt-1">
                            <a 
                                href="{{ route($bookingRoutePrefix . '.invoices.show', $booking->invoice->id) }}"
                                wire:navigate
                                class="text-[#B89B6B] hover:underline"
                                style="text-decoration: underline; text-underline-offset: 3px; font-weight: 500;"
                            >
                                {{ $booking->invoice->invoice_number }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </aside>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7;">
                Update Booking
            </button>
        </div>
    </form>
</div>
