<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use Livewire\Component;

class Edit extends Component
{
    public Booking $booking;

    public string $customer_id = '';

    public string $status = '';

    public string $service_id = '';

    public string $employee_id = '';

    public function mount(Booking $booking)
    {
        $this->booking = $booking;

        $this->customer_id =
            $booking->customer_id;

        $this->status =
            $booking->status;

        $service =
            $booking->services()->first();

        if ($service) {

            $this->service_id =
                (string) $service->service_id;

            $this->employee_id =
                (string) ($service->employee_id ?? '');
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'customer_id' => [
                'required',
                'exists:customers,id'
            ],

            'service_id' => [
                'required',
                'exists:services,id'
            ],

            'employee_id' => [
                'required',
                'exists:employees,id'
            ],

            'status' => [
                'required',
                'in:pending,confirmed,completed,cancelled,no_show'
            ],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $employee = Employee::findOrFail($validated['employee_id']);

        $bookingService = BookingService::query()
            ->where('booking_id', $this->booking->id)
            ->first();

        if (!$bookingService) {
            session()->flash('error', 'Detail booking tidak ditemukan.');
            return;
        }

        $start = \Carbon\Carbon::parse($bookingService->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        // Conflict check excluding current booking
        $conflict = BookingService::query()
            ->where('booking_id', '!=', $this->booking->id)
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

        $commissionPercentage = $employee->commission_percentage;
        $commissionAmount = $service->price * ($commissionPercentage / 100);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $service, $end, $bookingService, $commissionPercentage, $commissionAmount) {
            $this->booking->update([
                'customer_id' => $validated['customer_id'],
                'status' => $validated['status'],
                'total_amount' => $service->price,
                'booking_end' => $end,
            ]);

            $bookingService->update([
                'service_id' => $validated['service_id'],
                'employee_id' => $validated['employee_id'],
                'price' => $service->price,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
                'duration_minutes' => $service->duration_minutes,
                'end_time' => $end,
            ]);
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
        return view(
            'livewire.bookings.edit',
            [
                'customers' =>
                    Customer::orderBy('name')->get(),

                'employees' =>
                    Employee::where(
                        'employment_status',
                        'active'
                    )->orderBy('full_name')->get(),

                'services' =>
                    Service::where(
                        'is_active',
                        true
                    )->orderBy('name')->get(),
            ]
        )->layout('layouts.app');
    }
}
