<?php

namespace App\Livewire\Cashier;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Attendance;
use Livewire\Component;

class Dashboard extends Component
{
    public int $todayBookings = 0;

    public int $todayCustomers = 0;

    public float $todayRevenue = 0;

    public int $todayAttendances = 0;

    public function mount()
    {
        $this->todayBookings =
            Booking::query()
            ->whereDate(
                'booking_date',
                today()
            )
            ->count();

        $this->todayCustomers =
            Customer::count();

        $this->todayRevenue =
            Invoice::query()
            ->where(
                'payment_status',
                'paid'
            )
            ->whereDate(
                'created_at',
                today()
            )
            ->sum('grand_total');

        $this->todayAttendances =
            Attendance::query()
            ->whereDate(
                'attendance_date',
                today()
            )
            ->count();
    }

    public function render()
    {
        return view(
            'livewire.cashier.dashboard'
        )->layout('layouts.app');
    }
}