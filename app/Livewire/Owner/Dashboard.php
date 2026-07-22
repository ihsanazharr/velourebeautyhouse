<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payroll;

class Dashboard extends Component
{
    public int $totalCustomers = 0;
    public int $totalEmployees = 0;
    public int $todayBookings = 0;
    public int $lowStockProducts = 0;
    public float $monthlyRevenue = 0;
    public float $monthlyPayroll = 0;
    public int $unpaidInvoices = 0;
    public int $pendingBookings = 0;

    public function mount()
    {
        $this->totalCustomers = Customer::count();

        $this->totalEmployees = Employee::count();

        $this->todayBookings = Booking::whereDate(
            'booking_date',
            Carbon::today()
        )->count();

        $this->lowStockProducts = Product::whereColumn(
            'current_stock',
            '<=',
            'alert_stock'
        )->count();

        $this->monthlyRevenue = Invoice::query()
        ->where(
            'payment_status',
            'paid'
        )
        ->whereMonth(
            'created_at',
            Carbon::now()->month
        )
        ->whereYear(
            'created_at',
            Carbon::now()->year
        )
        ->sum('grand_total');

    $this->monthlyPayroll = Payroll::query()
        ->where(
            'status',
            'paid'
        )
        ->where(
            'month',
            Carbon::now()->month
        )
        ->where(
            'year',
            Carbon::now()->year
        )
        ->sum('net_salary');

    $this->unpaidInvoices = Invoice::query()
        ->where(
            'payment_status',
            '!=',
            'paid'
        )
        ->count();

    $this->pendingBookings = Booking::query()
        ->where(
            'status',
            'pending'
        )
        ->count();
    }

    public function render()
    {
        return view('livewire.owner.dashboard')
        ->layout('layouts.app');
    }
}