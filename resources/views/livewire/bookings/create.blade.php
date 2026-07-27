<div class="p-6 lg:p-10" style="background-color: #FAF9F7;">

    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
        </div>
        <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
            Tambah Booking
        </h1>
    </div>

    <style>
        .veloure-form :where(select, input[type="date"], input[type="time"], input[type="text"], textarea) {
            border-radius: 0;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: white;
            width: 100%;
        }
        .veloure-form :where(select, input[type="date"], input[type="time"], input[type="text"], textarea):focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 1px #B89B6B;
        }
        .veloure-form label {
            font-family: 'Poppins', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #3A3733;
            display: block;
            margin-bottom: 8px;
        }
    </style>

    <form wire:submit="save" class="veloure-form">

        <div class="bg-white border p-8 max-w-2xl" style="border-color:#EDEAE4;">

            <div class="mb-6 relative" x-data="{ open: true }" @click.away="open = false">

                <label>Nama Pelanggan (Walk-In / Terdaftar)</label>

                <input
                    type="text"
                    wire:model.live="customer_name"
                    placeholder="Ketik nama pelanggan..."
                    @focus="open = true"
                    autocomplete="off"
                >

                @if(!empty($customer_suggestions))
                    <div 
                        x-show="open" 
                        class="absolute z-50 left-0 right-0 mt-1 bg-white border shadow-lg max-h-60 overflow-y-auto"
                        style="border-color: #EDEAE4;"
                    >
                        @foreach($customer_suggestions as $suggestion)
                            <div
                                wire:click="selectCustomer('{{ $suggestion['id'] }}', '{{ $suggestion['name'] }}', '{{ $suggestion['phone'] }}')"
                                @click="open = false"
                                class="p-3 font-body text-sm cursor-pointer transition-colors duration-200 hover:bg-[#FAF9F7]"
                                style="border-bottom: 1px solid #EDEAE4;"
                            >
                                <div class="font-semibold text-[#1C1A18]">{{ $suggestion['name'] }}</div>
                                <div class="text-xs text-[#9C9690]">{{ $suggestion['phone'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />

            </div>

            <div class="mb-6">

                <label>Nomor WhatsApp / HP (Opsional)</label>

                <input
                    type="text"
                    wire:model="customer_phone"
                    placeholder="Contoh: 081234567890"
                >
                
                <x-input-error :messages="$errors->get('customer_phone')" class="mt-2" />

            </div>

            <div class="mb-6">

                <label>Service</label>

                <select
                    wire:model="service_id"
                >

                    <option value="">
                        Pilih Service
                    </option>

                    @foreach($services as $service)

                        <option
                            value="{{ $service->id }}"
                        >
                            {{ $service->name }}
                            &mdash;
                            Rp {{ number_format($service->price) }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-6">

                <label>Employee</label>

                <select
                    wire:model="employee_id"
                >

                    <option value="">
                        Pilih Employee
                    </option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                        >
                            {{ $employee->full_name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

                <div>

                    <label>Tanggal</label>

                    <input
                        type="date"
                        wire:model="booking_date"
                    >

                </div>

                <div>

                    <label>Jam Mulai</label>

                    <input
                        type="time"
                        wire:model="booking_time"
                    >

                </div>

            </div>

            <div class="mb-8">

                <label>Catatan</label>

                <textarea
                    wire:model="notes"
                    rows="4"
                ></textarea>

            </div>

            <button
                type="submit"
                class="font-body text-xs tracking-[0.15em] uppercase transition-colors duration-300 hover:opacity-90"
                style="
                    background:#1C1A18;
                    color:#FAF9F7;
                    padding:14px 28px;
                    border:none;
                "
            >
                Simpan Booking
            </button>

        </div>

    </form>

</div>