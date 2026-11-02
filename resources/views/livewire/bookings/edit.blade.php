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
        .veloure-form input:not([type=checkbox]),
        .veloure-form select,
        .veloure-form textarea {
            border-radius: 8px;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: #FAF9F7;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .veloure-form input:not([type=checkbox]):focus,
        .veloure-form select:focus,
        .veloure-form textarea:focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12);
            background: white;
        }
        .veloure-form label {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #3A3733;
            margin-bottom: 6px;
            font-weight: 500;
        }
        .veloure-form textarea { resize: vertical; min-height: 90px; }
    </style>

    <form wire:submit="save" class="veloure-form w-full">
        <div class="grid lg:grid-cols-[1fr_320px] gap-6">
            <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4; border-radius: 12px;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color:#B89B6B;">Detail Booking</p>

                <div class="space-y-6">
                    <div>
                        <label>Customer (Opsional / Walk-In)</label>
                        <select wire:model="customer_id">
                            <option value="">Tanpa Customer / Walk-In</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? '-' }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>

                    {{-- Layanan & Stylist Multi-selection --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="!mb-0">Layanan & Terapis (Stylist) <span style="color:#B5402C;">*</span></label>
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

                        <div class="space-y-4">
                            @foreach($selected_services as $index => $item)
                                <div class="flex gap-3 items-start p-4 bg-[#FAF9F7] border border-[#EDEAE4]" wire:key="service-slot-{{ $index }}" style="border-radius: 6px;">
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="!text-[10px] !mb-1 text-[#9C9690]">Layanan</label>
                                            <select wire:model.live="selected_services.{{ $index }}.service_id">
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
                                            <select wire:model.live="selected_services.{{ $index }}.employee_id">
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
                        <x-input-error :messages="$errors->get('selected_services')" class="mt-2" />
                    </div>

                    {{-- Beli Produk (Search Dropdown + Qty) --}}
                    <div class="p-5 border border-[#EDEAE4] bg-[#FAF9F7]" style="border-radius: 8px;">
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label>Tanggal <span style="color:#B5402C;">*</span></label>
                            <input type="date" wire:model.live="booking_date">
                            <x-input-error :messages="$errors->get('booking_date')" class="mt-2" />
                        </div>

                        <div>
                            <label>Jam Mulai <span style="color:#B5402C;">*</span></label>
                            <input type="time" wire:model.live="booking_time">
                            <x-input-error :messages="$errors->get('booking_time')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label>Status <span style="color:#B5402C;">*</span></label>
                        <select wire:model="status">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No Show</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <label>Catatan</label>
                        <textarea wire:model.live="notes" rows="3" placeholder="Tambahkan catatan khusus jika ada..."></textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>
            </div>

            {{-- Sidebar Ringkasan --}}
            <aside class="bg-white border p-6 h-fit" style="border-color:#EDEAE4; border-radius: 12px;">
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color:#B89B6B;">Ringkasan</p>
                <div class="space-y-4 font-body text-sm" style="color:#3A3733;">
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Jadwal Mulai</p>
                        <p class="mt-1 font-medium text-[#1C1A18]">
                            @if($booking_date && $booking_time)
                                {{ \Carbon\Carbon::parse($booking_date . ' ' . $booking_time)->format('d M Y H:i') }} WIB
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Total Estimasi</p>
                        <p class="mt-1 font-semibold text-[#1C1A18] text-base">
                            Rp {{ number_format($this->calculateTotal(), 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-[#9C9690] mt-0.5">
                            {{ count($selected_services) }} Layanan &middot; {{ count($selected_products) }} Produk
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em]" style="color:#9C9690;">Catatan</p>
                        <p class="mt-1 whitespace-pre-line">{{ $notes ?: '-' }}</p>
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
            <button type="submit" class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7; border-radius: 6px;">
                Update Booking
            </button>
        </div>
    </form>
</div>
