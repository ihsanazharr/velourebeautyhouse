<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\BookingCustomer;
use App\Models\BookingProduct;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public Booking $booking;

    public string $customer_id = '';

    public string $status = '';

    public array $selected_services = [
        ['service_id' => '', 'employee_id' => '']
    ];

    public array $selected_products = [];

    public string $product_search = '';

    public array $product_suggestions = [];

    public string $booking_date = '';

    public string $booking_time = '';

    public ?string $notes = null;

    // Backwards compatibility for single-service access / tests
    public string $service_id = '';
    public string $employee_id = '';

    public function mount(Booking $booking): void
    {
        $this->booking = $booking;

        $this->customer_id = (string) $booking->customer_id;
        $this->status = $booking->status;
        $this->notes = $booking->notes;

        if ($booking->booking_start) {
            $this->booking_date = $booking->booking_start->format('Y-m-d');
            $this->booking_time = $booking->booking_start->format('H:i');
        } else {
            $this->booking_date = $booking->booking_date ? Carbon::parse($booking->booking_date)->format('Y-m-d') : now()->toDateString();
            $this->booking_time = '10:00';
        }

        $services = $booking->services()->orderBy('start_time')->get();

        if ($services->isNotEmpty()) {
            $this->selected_services = [];
            foreach ($services as $bs) {
                $this->selected_services[] = [
                    'service_id' => (string) $bs->service_id,
                    'employee_id' => (string) ($bs->employee_id ?? ''),
                ];
            }

            $first = $services->first();
            $this->service_id = (string) $first->service_id;
            $this->employee_id = (string) ($first->employee_id ?? '');
        } else {
            $this->selected_services = [
                ['service_id' => '', 'employee_id' => '']
            ];
        }

        $existingProducts = $booking->products()->with('product')->get();
        $this->selected_products = [];
        foreach ($existingProducts as $bp) {
            $this->selected_products[] = [
                'product_id' => (string) $bp->product_id,
                'name' => $bp->product?->name ?? 'Produk',
                'sku' => $bp->product?->sku ?? '',
                'price' => (float) $bp->unit_price,
                'quantity' => (int) $bp->quantity,
                'max_stock' => (int) ($bp->product?->current_stock ?? 0) + (int) $bp->quantity,
            ];
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
                $this->selected_products[$key]['quantity'] = ((int) ($this->selected_products[$key]['quantity'] ?? 1)) + 1;
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

    public function updatedServiceId($value): void
    {
        if (!empty($value)) {
            if (empty($this->selected_services)) {
                $this->selected_services = [['service_id' => (string) $value, 'employee_id' => $this->employee_id]];
            } else {
                $this->selected_services[0]['service_id'] = (string) $value;
            }
        }
    }

    public function updatedEmployeeId($value): void
    {
        if (!empty($value)) {
            if (empty($this->selected_services)) {
                $this->selected_services = [['service_id' => $this->service_id, 'employee_id' => (string) $value]];
            } else {
                $this->selected_services[0]['employee_id'] = (string) $value;
            }
        }
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

    protected $messages = [
        'customer_id.exists' => 'Data customer tidak valid',
        'status.required' => 'Mohon untuk diisi',
        'booking_date.required' => 'Mohon untuk diisi',
        'booking_time.required' => 'Mohon untuk diisi',
        'selected_services.required' => 'Mohon pilih minimal 1 layanan',
        'selected_services.min' => 'Mohon pilih minimal 1 layanan',
        'selected_services.*.service_id.required' => 'Mohon untuk diisi',
        'selected_services.*.employee_id.required' => 'Mohon untuk diisi',
        'selected_products.*.product_id.required' => 'Mohon untuk diisi',
        'selected_products.*.quantity.required' => 'Mohon untuk diisi',
        'selected_products.*.quantity.min' => 'Jumlah minimal 1',
    ];

    public function save()
    {
        // Sync legacy properties if modified
        if (!empty($this->service_id)) {
            if (empty($this->selected_services)) {
                $this->selected_services = [['service_id' => (string) $this->service_id, 'employee_id' => (string) $this->employee_id]];
            } else {
                $this->selected_services[0]['service_id'] = (string) $this->service_id;
                if (!empty($this->employee_id)) {
                    $this->selected_services[0]['employee_id'] = (string) $this->employee_id;
                }
            }
        }

        $validated = $this->validate([
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],
            'status' => [
                'required',
                'in:pending,confirmed,completed,cancelled,no_show',
            ],
            'booking_date' => [
                'required',
                'date',
            ],
            'booking_time' => [
                'required',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'selected_services' => [
                'required',
                'array',
                'min:1',
            ],
            'selected_services.*.service_id' => [
                'required',
                'exists:services,id',
            ],
            'selected_services.*.employee_id' => [
                'required',
                'exists:employees,id',
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

            // Check if stylist has a conflict during this specific range, excluding current booking
            $conflict = BookingService::query()
                ->where('booking_id', '!=', $this->booking->id)
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

        // Validate stock and prepare products
        $existingBookingProducts = $this->booking->products()->with('product')->get();
        $oldReservedByProduct = $existingBookingProducts->pluck('quantity', 'product_id')->all();

        $products_to_create = [];
        $products_total = 0;

        foreach ($this->selected_products as $pIndex => $pItem) {
            $product = Product::findOrFail($pItem['product_id']);
            $qty = max(1, (int) $pItem['quantity']);
            $availableStock = $product->current_stock + ($oldReservedByProduct[$product->id] ?? 0);

            if ($availableStock < $qty) {
                $this->addError("selected_products.{$pIndex}.quantity", "Stok produk '{$product->name}' tidak mencukupi (tersisa {$availableStock}).");
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

        DB::transaction(function () use ($validated, $services_to_create, $products_to_create, $start, $current_start, $services_total, $products_total, $existingBookingProducts) {
            $grand_total = $services_total + $products_total;
            $customerId = !empty($validated['customer_id']) ? $validated['customer_id'] : null;

            $this->booking->update([
                'customer_id' => $customerId,
                'status' => $validated['status'],
                'booking_date' => $validated['booking_date'],
                'booking_start' => $start,
                'booking_end' => $current_start,
                'total_amount' => $grand_total,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($customerId) {
                BookingCustomer::updateOrCreate(
                    ['booking_id' => $this->booking->id],
                    ['customer_id' => $customerId]
                );
            } else {
                BookingCustomer::where('booking_id', $this->booking->id)->delete();
            }

            // Re-create booking services
            $this->booking->services()->delete();

            foreach ($services_to_create as $sData) {
                BookingService::create([
                    'booking_id' => $this->booking->id,
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

            // Reconcile products & stock
            foreach ($existingBookingProducts as $oldBp) {
                $oldBp->product?->increment('current_stock', $oldBp->quantity);
                StockMovement::create([
                    'product_id' => $oldBp->product_id,
                    'movement_date' => now()->toDateString(),
                    'type' => 'in',
                    'quantity' => $oldBp->quantity,
                    'notes' => 'Penyesuaian stok Edit Booking ' . $this->booking->booking_number,
                ]);
            }
            $this->booking->products()->delete();

            foreach ($products_to_create as $pData) {
                BookingProduct::create([
                    'booking_id' => $this->booking->id,
                    'product_id' => $pData['product_id'],
                    'quantity' => $pData['quantity'],
                    'unit_price' => $pData['unit_price'],
                    'subtotal' => $pData['subtotal'],
                ]);

                $pData['product']->decrement('current_stock', $pData['quantity']);

                StockMovement::create([
                    'product_id' => $pData['product_id'],
                    'movement_date' => now()->toDateString(),
                    'type' => 'out',
                    'quantity' => $pData['quantity'],
                    'notes' => 'Penjualan Edit Booking ' . $this->booking->booking_number,
                ]);
            }

            if ($this->booking->invoice) {
                $this->booking->invoice->update([
                    'customer_id' => $customerId,
                    'show_customer_name' => !empty($customerId) ? ($this->booking->invoice->show_customer_name ?? true) : false,
                    'subtotal' => $grand_total,
                    'grand_total' => max(0, $grand_total - (float) $this->booking->invoice->discount),
                ]);
            }
        });

        session()->flash(
            'success',
            'Booking berhasil diperbarui.'
        );

        $routePrefix = auth()->user()?->hasRole('kasir') ? 'kasir' : 'owner';

        return redirect()->route(
            $routePrefix . '.bookings.index'
        );
    }

    public function render()
    {
        $selectedEmployeeIds = collect($this->selected_services)->pluck('employee_id')->filter()->all();
        $selectedServiceIds = collect($this->selected_services)->pluck('service_id')->filter()->all();

        return view(
            'livewire.bookings.edit',
            [
                'customers' =>
                    Customer::orderBy('name')->get(),

                'employees' =>
                    Employee::where('employment_status', 'active')
                        ->orWhereIn('id', $selectedEmployeeIds)
                        ->orderBy('full_name')
                        ->get(),

                'services' =>
                    Service::where('is_active', true)
                        ->orWhereIn('id', $selectedServiceIds)
                        ->orderBy('name')
                        ->get(),
            ]
        )->layout('layouts.app');
    }
}
