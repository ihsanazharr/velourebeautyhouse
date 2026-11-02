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

        <div class="bg-white border p-8 w-full" style="border-color:#EDEAE4;">

            <div class="mb-6 relative" x-data="{ open: true }" @click.away="open = false">

                <label>Nama Pelanggan (Opsional / Walk-In)</label>

                <input
                    type="text"
                    wire:model.live="customer_name"
                    placeholder="Ketik nama pelanggan (kosongkan jika tanpa nama)..."
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
                <div class="flex items-center justify-between mb-4">
                    <label class="!mb-0">Layanan & Terapis (Stylist)</label>
                    <button 
                        type="button" 
                        wire:click="addService"
                        class="font-body text-xs px-3 py-1.5 border transition-all duration-200 inline-flex items-center gap-1.5 hover:bg-[#FAF9F7]"
                        style="color: #B89B6B; border-color: #EDEAE4; border-radius: 4px;"
                    >
                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Tambah Layanan
                    </button>
                </div>
                <x-input-error :messages="$errors->get('selected_services')" class="mb-3" />

                <div class="space-y-4">
                    @foreach($selected_services as $index => $item)
                        <div class="flex gap-3 items-start p-4 bg-[#FAF9F7] border border-[#EDEAE4]" wire:key="service-slot-{{ $index }}" style="border-radius: 6px;">
                            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="!text-[10px] !mb-1 text-[#9C9690]">Layanan</label>
                                    <select wire:model="selected_services.{{ $index }}.service_id">
                                        <option value="">Pilih Service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->name }} &mdash; Rp {{ number_format($service->price) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('selected_services.'.$index.'.service_id')" class="mt-1" />
                                </div>
                                <div>
                                    <label class="!text-[10px] !mb-1 text-[#9C9690]">Stylist</label>
                                    <select wire:model="selected_services.{{ $index }}.employee_id">
                                        <option value="">Pilih Stylist</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('selected_services.'.$index.'.employee_id')" class="mt-1" />
                                </div>
                            </div>

                            @if(count($selected_services) > 1)
                                <button 
                                    type="button" 
                                    wire:click="removeService({{ $index }})"
                                    class="text-red-500 font-body text-xs mt-6 transition-opacity hover:opacity-80"
                                    title="Hapus Layanan"
                                >
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Beli Produk (Search Dropdown + Qty) --}}
            <div class="mb-6 p-5 border border-[#EDEAE4] bg-[#FAF9F7]" style="border-radius: 8px;">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="!mb-0 !text-xs font-semibold text-[#1C1A18]">Beli Produk (Opsional)</label>
                        <p class="font-body text-[11px] text-[#9C9690] mt-0.5">Pilih produk ritel yang dibeli oleh pelanggan saat booking ini</p>
                    </div>
                    <span class="font-body text-[10px] tracking-wider uppercase px-2 py-0.5 bg-[#EDEAE4] text-[#3A3733]" style="border-radius: 4px;">
                        {{ count($selected_products) }} Produk
                    </span>
                </div>

                {{-- Product Search Input with Dropdown --}}
                <div class="relative mb-4" x-data="{ open: true }" @click.away="open = false">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="product_search"
                            placeholder="Cari produk (nama / SKU / barcode)..."
                            @focus="open = true"
                            autocomplete="off"
                            class="!bg-white"
                        >
                        @if($product_search)
                            <button 
                                type="button" 
                                wire:click="$set('product_search', '')" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs"
                            >
                                ✕
                            </button>
                        @endif
                    </div>

                    @if(!empty($product_suggestions))
                        <div 
                            x-show="open" 
                            class="absolute z-50 left-0 right-0 mt-1 bg-white border shadow-lg max-h-60 overflow-y-auto"
                            style="border-color: #EDEAE4; border-radius: 6px;"
                        >
                            @foreach($product_suggestions as $suggestion)
                                <div
                                    wire:click="addProduct('{{ $suggestion['id'] }}')"
                                    @click="open = false"
                                    class="p-3 font-body text-sm cursor-pointer transition-colors duration-200 hover:bg-[#FAF9F7] flex items-center justify-between"
                                    style="border-bottom: 1px solid #EDEAE4;"
                                >
                                    <div>
                                        <div class="font-medium text-[#1C1A18]">{{ $suggestion['name'] }}</div>
                                        <div class="text-xs text-[#9C9690]">
                                            @if($suggestion['sku'])
                                                SKU: {{ $suggestion['sku'] }} &middot;
                                            @endif
                                            Stok: <span class="font-semibold {{ $suggestion['current_stock'] > 5 ? 'text-[#5C7A5C]' : 'text-[#B5402C]' }}">{{ $suggestion['current_stock'] }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-[#B89B6B]">Rp {{ number_format($suggestion['selling_price'], 0, ',', '.') }}</div>
                                        <span class="text-[10px] text-[#5C7A5C] bg-[#EAF1EA] px-1.5 py-0.5 rounded font-medium">+ Tambah</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(strlen($product_search) >= 2)
                        <div 
                            x-show="open" 
                            class="absolute z-50 left-0 right-0 mt-1 bg-white border p-3 text-center text-xs text-[#9C9690] shadow-lg"
                            style="border-color: #EDEAE4; border-radius: 6px;"
                        >
                            Tidak ada produk ditemukan untuk "{{ $product_search }}"
                        </div>
                    @endif
                </div>

                {{-- Selected Products Table / List --}}
                @if(!empty($selected_products))
                    <div class="bg-white border border-[#EDEAE4] overflow-hidden" style="border-radius: 6px;">
                        <div class="p-3 bg-[#FAF9F7] border-b border-[#EDEAE4] grid grid-cols-12 text-[10px] uppercase tracking-wider font-semibold text-[#9C9690]">
                            <div class="col-span-5 sm:col-span-6">Produk</div>
                            <div class="col-span-3 sm:col-span-2 text-right">Harga</div>
                            <div class="col-span-2 sm:col-span-2 text-center">Qty</div>
                            <div class="col-span-2 sm:col-span-2 text-right">Subtotal</div>
                        </div>
                        <div class="divide-y divide-[#EDEAE4]">
                            @foreach($selected_products as $pIndex => $pItem)
                                <div class="p-3 grid grid-cols-12 items-center gap-2 text-xs font-body" wire:key="selected-prod-{{ $pIndex }}">
                                    <div class="col-span-5 sm:col-span-6">
                                        <div class="font-medium text-[#1C1A18]">{{ $pItem['name'] }}</div>
                                        @if(!empty($pItem['sku']))
                                            <div class="text-[10px] text-[#9C9690]">SKU: {{ $pItem['sku'] }}</div>
                                        @endif
                                        <x-input-error :messages="$errors->get('selected_products.'.$pIndex.'.quantity')" class="mt-1" />
                                    </div>
                                    <div class="col-span-3 sm:col-span-2 text-right text-[#3A3733]">
                                        Rp {{ number_format($pItem['price'], 0, ',', '.') }}
                                    </div>
                                    <div class="col-span-2 sm:col-span-2 flex items-center justify-center gap-1">
                                        <input 
                                            type="number" 
                                            min="1" 
                                            max="{{ $pItem['max_stock'] ?? 999 }}"
                                            wire:model.live="selected_products.{{ $pIndex }}.quantity"
                                            class="!w-16 !p-1.5 !text-center !text-xs !bg-[#FAF9F7] border border-[#EDEAE4] rounded"
                                        >
                                    </div>
                                    <div class="col-span-2 sm:col-span-2 flex items-center justify-end gap-2">
                                        <span class="font-semibold text-[#1C1A18]">
                                            Rp {{ number_format(((float)$pItem['price']) * max(1, (int)($pItem['quantity'] ?? 1)), 0, ',', '.') }}
                                        </span>
                                        <button 
                                            type="button" 
                                            wire:click="removeProduct({{ $pIndex }})" 
                                            class="text-red-400 hover:text-red-600 transition-colors p-1"
                                            title="Hapus Produk"
                                        >
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

                <div>

                    <label>Tanggal</label>

                    <input
                        type="date"
                        wire:model="booking_date"
                    >
                    <x-input-error :messages="$errors->get('booking_date')" class="mt-2" />

                </div>

                <div>

                    <label>Jam Mulai</label>

                    <input
                        type="time"
                        wire:model="booking_time"
                    >
                    <x-input-error :messages="$errors->get('booking_time')" class="mt-2" />

                </div>

            </div>

            <div class="mb-8">

                <label>Catatan</label>

                <textarea
                    wire:model="notes"
                    rows="4"
                ></textarea>

            </div>

            {{-- Total Summary Banner & Submit Button --}}
            <div class="pt-6 border-t border-[#EDEAE4] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] uppercase tracking-wider text-[#9C9690] font-body">Estimasi Total Biaya</div>
                    <div class="font-display text-3xl font-semibold text-[#1C1A18]">
                        Rp {{ number_format($this->calculateTotal(), 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-[#9C9690] font-body">
                        {{ count($selected_services) }} Layanan &middot; {{ count($selected_products) }} Produk
                    </div>
                </div>

                <button
                    type="submit"
                    class="font-body text-xs tracking-[0.15em] uppercase transition-all duration-300 hover:opacity-90 flex items-center gap-2"
                    style="
                        background:#1C1A18;
                        color:#FAF9F7;
                        padding:14px 28px;
                        border:none;
                        border-radius: 6px;
                    "
                >
                    <span>Simpan & Selesaikan Pembayaran</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </div>

        </div>

    </form>

    {{-- Booking History Section --}}
    @if ($past_bookings && $past_bookings->isNotEmpty())
        <div class="bg-white border p-8 mt-8 w-full" style="border-color:#EDEAE4; border-radius: 12px;">
            <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Riwayat Booking Pelanggan</p>
            
            <div class="overflow-x-auto">
                <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-xs" style="border-collapse: collapse;">
                    @php
                        $routePrefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';
                    @endphp
                    <thead>
                        <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">No. Booking</th>
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Tanggal</th>
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Layanan (Stylist)</th>
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Total Harga</th>
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Status</th>
                            <th class="text-left p-3 text-[10px] tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($past_bookings as $pBooking)
                            <tr style="border-bottom: 1px solid #EDEAE4;">
                                <td class="p-3 text-[#1C1A18] font-semibold">{{ $pBooking->booking_number }}</td>
                                <td class="p-3 text-[#3A3733]">{{ \Carbon\Carbon::parse($pBooking->booking_date)->translatedFormat('d M Y') }}</td>
                                <td class="p-3 text-[#3A3733]">
                                    <div class="space-y-1">
                                        @foreach ($pBooking->services as $bService)
                                            <div>
                                                {{ $bService->service?->name ?? 'Service' }} 
                                                <span class="text-[#9C9690]">
                                                    ({{ $bService->employee?->full_name ?? 'Terapis' }})
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="p-3 text-[#1C1A18] font-medium">Rp {{ number_format($pBooking->total_amount) }}</td>
                                <td class="p-3">
                                    @php
                                        $statusStyles = match ($pBooking->status) {
                                            'completed' => 'background: #EAF1EA; color: #5C7A5C;',
                                            'confirmed' => 'background: #EAF1EA; color: #5C7A5C;',
                                            'pending' => 'background: #FBF3E8; color: #B89B6B;',
                                            'cancelled' => 'background: #FBEAE7; color: #B5402C;',
                                            default => 'background: #EDEAE4; color: #9C9690;',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 uppercase text-[9px] tracking-[0.08em] font-semibold inline-block" style="{{ $statusStyles }} border-radius: 4px;">
                                        {{ $pBooking->status }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if ($pBooking->invoice)
                                        <a 
                                            href="{{ route($routePrefix . '.invoices.show', $pBooking->invoice->id) }}"
                                            wire:navigate
                                            class="font-semibold text-[#B89B6B] hover:underline"
                                            style="text-decoration: underline; text-underline-offset: 2px;"
                                        >
                                            {{ $pBooking->invoice->invoice_number }}
                                        </a>
                                    @else
                                        <span class="text-[#9C9690]">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>