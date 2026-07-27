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

    public string $service_id = '';

    public string $employee_id = '';

    public string $booking_date = '';

    public string $booking_time = '';

    public ?string $notes = null;

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
            'service_id' => [
                'required',
                'exists:services,id'
            ],
            'employee_id' => [
                'required',
                'exists:employees,id'
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
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $start = \Carbon\Carbon::parse(
            $validated['booking_date'] . ' ' . $validated['booking_time']
        );

        $end = $start->copy()->addMinutes($service->duration_minutes);

        $conflict = BookingService::query()
            ->where('employee_id', $validated['employee_id'])
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();

        if ($conflict) {
            $this->addError('employee_id', 'Stylist sudah memiliki jadwal pada jam tersebut.');
            return;
        }

        DB::transaction(function () use ($validated, $service, $start, $end) {
            $customerId = $this->customer_id;

            if (empty($customerId)) {
                // Find or create customer by name and phone to prevent duplicate guest accounts
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
                'booking_end' => $end,
                'dp_amount' => 0,
                'total_amount' => $service->price,
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            BookingCustomer::create([
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
            ]);

            $employee = Employee::findOrFail($validated['employee_id']);
            $commissionPercentage = $employee->commission_percentage;
            $commissionAmount = $service->price * ($commissionPercentage / 100);

            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'employee_id' => $validated['employee_id'],
                'price' => $service->price,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
                'duration_minutes' => $service->duration_minutes,
                'start_time' => $start,
                'end_time' => $end,
            ]);
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
            ]
        )->layout('layouts.app');
    }
}