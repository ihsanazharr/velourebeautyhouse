<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\BookingCustomer;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
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
        ]);

        $start = \Carbon\Carbon::parse(
            $validated['booking_date'] . ' ' . $validated['booking_time']
        );

        $services_to_create = [];
        $current_start = $start->copy();
        $total_price = 0;

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

            $total_price += $service->price;
            $current_start = $service_end;
        }

        DB::transaction(function () use ($validated, $services_to_create, $start, $current_start, $total_price) {
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
        });

        session()->flash(
            'success',
            'Booking berhasil dibuat.'
        );

        $routeName = auth()->user()->hasRole('owner') 
            ? 'owner.bookings.index' 
            : 'kasir.bookings.index';

        return redirect()->route($routeName);
    }

    public function render()
    {
        $past_bookings = collect();
        if (!empty($this->customer_id)) {
            $past_bookings = Booking::where('customer_id', $this->customer_id)
                ->with('services.service', 'services.employee', 'invoice')
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