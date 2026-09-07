<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\BookingCustomer;
use App\Models\BookingProduct;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public string $customer_id = '';

    public string $customer_name = '';

    public string $customer_phone = '';

    public array $customer_suggestions = [];

    public array $selected_services = [
        ['service_id' => '', 'employee_id' => '']
    ];

    public array $selected_products = [];

    public string $product_search = '';

    public array $product_suggestions = [];

    public string $booking_date = '';

    public string $booking_time = '';

    public ?string $notes = null;

    public function mount(): void
    {
        $customerId = request()->query('customer_id');
        if ($customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $this->customer_id = $customer->id;
                $this->customer_name = $customer->name;
                $this->customer_phone = $customer->phone ?? '';
            }
        }

        if (empty($this->booking_date)) {
            $this->booking_date = now()->toDateString();
        }
    }

    public function addService(): void
    {
        $this->selected_services[] = ['service_id' => '', 'employee_id' => ''];
    }

    public function removeService(int $index): void
    {
        unset($this->selected_services[$index]);
        $this->selected_services = array_values($this->selected_services);
    }

    public function updatedCustomerName(): void
    {
        $this->customer_id = ''; 
        
        if (strlen($this->customer_name) >= 1) {
            $this->customer_suggestions = Customer::query()
                ->where('is_active', true)
                ->where('name', 'like', '%' . $this->customer_name . '%')
                ->take(5)
                ->get()
                ->toArray();
        } else {
            $this->customer_suggestions = [];
        }
    }

    public function selectCustomer(string $id, string $name, string $phone): void
    {
        $this->customer_id = $id;
        $this->customer_name = $name;
        $this->customer_phone = $phone;
        $this->customer_suggestions = [];
    }

    public function updatedProductSearch(): void
    {
        if (strlen($this->product_search) >= 1) {
            $alreadySelected = collect($this->selected_products)->pluck('product_id')->all();
            $this->product_suggestions = Product::query()
                ->where('is_active', true)
                ->where('current_stock', '>', 0)
                ->whereNotIn('id', $alreadySelected)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->product_search . '%')
                      ->orWhere('sku', 'like', '%' . $this->product_search . '%');
                })
                ->take(8)
                ->get()
                ->toArray();
        } else {
            $this->product_suggestions = [];
        }
    }

    public function addProduct(string $productId): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        foreach ($this->selected_products as $key => $p) {
            if ($p['product_id'] === $productId) {
                $this->selected_products[$key]['quantity'] = min(
                    (int) $product->current_stock,
                    ((int) ($this->selected_products[$key]['quantity'] ?? 1)) + 1
                );
                $this->product_search = '';
                $this->product_suggestions = [];
                return;
            }
        }

        $this->selected_products[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => (float) $product->selling_price,
            'quantity' => 1,
            'max_stock' => (int) $product->current_stock,
        ];

        $this->product_search = '';
        $this->product_suggestions = [];
    }

    public function removeProduct(int $index): void
    {
        unset($this->selected_products[$index]);
        $this->selected_products = array_values($this->selected_products);
    }

    public function calculateTotal(): float
    {
        $total = 0;
        foreach ($this->selected_services as $item) {
            if (!empty($item['service_id'])) {
                $service = Service::find($item['service_id']);
                if ($service) {
                    $total += (float) $service->price;
                }
            }
        }
        foreach ($this->selected_products as $p) {
            $total += ((float) ($p['price'] ?? 0)) * max(1, (int) ($p['quantity'] ?? 1));
        }
        return $total;
    }

    public function save()
    {
        $validated = $this->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],
            'customer_phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'booking_date' => [
                'required',
                'date'
            ],
            'booking_time' => [
                'required'
            ],
            'notes' => [
                'nullable'
            ],
            'selected_services' => [
                'required',
                'array',
                'min:1'
            ],
            'selected_services.*.service_id' => [
                'required',
                'exists:services,id'
            ],
            'selected_services.*.employee_id' => [
                'required',
                'exists:employees,id'
            ],
            'selected_products' => [
                'nullable',
                'array',
            ],
            'selected_products.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'selected_products.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $start = Carbon::parse(
            $validated['booking_date'] . ' ' . $validated['booking_time']
        );

        $services_to_create = [];
        $current_start = $start->copy();
        $services_total = 0;

        foreach ($this->selected_services as $index => $item) {
            $service = Service::findOrFail($item['service_id']);
            $employee = Employee::findOrFail($item['employee_id']);
            $service_end = $current_start->copy()->addMinutes($service->duration_minutes);

            // Check if stylist has a conflict during this specific range
            $conflict = BookingService::query()
                ->where('employee_id', $item['employee_id'])
                ->where(function ($query) use ($current_start, $service_end) {
                    $query->where('start_time', '<', $service_end)
                          ->where('end_time', '>', $current_start);
                })
                ->exists();

            if ($conflict) {
                $this->addError("selected_services.{$index}.employee_id", "Stylist {$employee->full_name} sudah memiliki jadwal pada jam tersebut (" . $current_start->format('H:i') . " - " . $service_end->format('H:i') . ").");
                return;
            }

            $commissionPercentage = $employee->commission_percentage;
            $commissionAmount = $service->price * ($commissionPercentage / 100);

            $services_to_create[] = [
                'service_id' => $service->id,
                'employee_id' => $employee->id,
                'price' => $service->price,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
                'duration_minutes' => $service->duration_minutes,
                'start_time' => $current_start->copy(),
                'end_time' => $service_end->copy(),
            ];

            $services_total += $service->price;
            $current_start = $service_end;
        }

        // Validate stock and calculate products
        $products_to_create = [];
        $products_total = 0;

        foreach ($this->selected_products as $pIndex => $pItem) {
            $product = Product::findOrFail($pItem['product_id']);
            $qty = max(1, (int) $pItem['quantity']);

            if ($product->current_stock < $qty) {
                $this->addError("selected_products.{$pIndex}.quantity", "Stok produk '{$product->name}' tidak mencukupi (tersisa {$product->current_stock}).");
                return;
            }

            $unitPrice = (float) $product->selling_price;
            $subtotal = $unitPrice * $qty;
            $products_total += $subtotal;

            $products_to_create[] = [
                'product' => $product,
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        $total_price = $services_total + $products_total;
        $createdInvoice = null;

        DB::transaction(function () use ($validated, $services_to_create, $products_to_create, $start, $current_start, $total_price, &$createdInvoice) {
            $customerId = $this->customer_id;

            if (empty($customerId)) {
                $phone = $this->customer_phone ?: '080000000000';
                $customer = Customer::where('name', $this->customer_name)
                    ->where('phone', $phone)
                    ->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $this->customer_name,
                        'phone' => $phone,
                        'is_active' => true,
                    ]);
                }
                $customerId = $customer->id;
            }

            $booking = Booking::create([
                'booking_number' => 'BK-' . now()->format('YmdHis'),
                'customer_id' => $customerId,
                'booking_date' => $validated['booking_date'],
                'booking_start' => $start,
                'booking_end' => $current_start,
                'dp_amount' => 0,
                'total_amount' => $total_price,
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            BookingCustomer::create([
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
            ]);

            foreach ($services_to_create as $sData) {
                BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $sData['service_id'],
                    'employee_id' => $sData['employee_id'],
                    'price' => $sData['price'],
                    'commission_percentage' => $sData['commission_percentage'],
                    'commission_amount' => $sData['commission_amount'],
                    'duration_minutes' => $sData['duration_minutes'],
                    'start_time' => $sData['start_time'],
                    'end_time' => $sData['end_time'],
                ]);
            }

            foreach ($products_to_create as $pData) {
                BookingProduct::create([
                    'booking_id' => $booking->id,
                    'product_id' => $pData['product_id'],
                    'quantity' => $pData['quantity'],
                    'unit_price' => $pData['unit_price'],
                    'subtotal' => $pData['subtotal'],
                ]);

                // Deduct stock
                $pData['product']->decrement('current_stock', $pData['quantity']);

                StockMovement::create([
                    'product_id' => $pData['product_id'],
                    'movement_date' => now()->toDateString(),
                    'type' => 'out',
                    'quantity' => $pData['quantity'],
                    'notes' => 'Penjualan melalui Booking ' . $booking->booking_number,
                ]);
            }

            // Create Invoice automatically
            $createdInvoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis'),
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
                'subtotal' => $total_price,
                'discount' => 0,
                'grand_total' => $total_price,
                'payment_status' => 'unpaid',
            ]);
        });

        session()->flash(
            'success',
            'Booking berhasil dibuat. Silakan selesaikan transaksi pembayaran.'
        );

        $routePrefix = auth()->user()->hasRole('owner') ? 'owner' : 'kasir';

        return redirect()->route($routePrefix . '.invoices.show', $createdInvoice->id);
    }

    public function render()
    {
        $past_bookings = collect();
        if (!empty($this->customer_id)) {
            $past_bookings = Booking::where('customer_id', $this->customer_id)
                ->with('services.service', 'services.employee', 'products.product', 'invoice')
                ->latest()
                ->get();
        }

        return view(
            'livewire.bookings.create',
            [
                'services' => Service::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(),

                'employees' => Employee::query()
                    ->where(
                        'employment_status',
                        'active'
                    )
                    ->orderBy('full_name')
                    ->get(),

                'past_bookings' => $past_bookings,
            ]
        )->layout('layouts.app');
    }
}