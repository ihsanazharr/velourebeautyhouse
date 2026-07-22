<?php

namespace App\Livewire\Reports;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\BookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Employee;

class Index extends Component
{
    use WithPagination;

    public float $monthlyRevenue  = 0;
    public float $monthlyPayroll  = 0;
    public int   $monthlyBookings = 0;
    public int   $unpaidInvoices  = 0;
    public array $topServices     = [];
    public array $topEmployees    = [];
    public array $topCustomers    = [];
    public float $grossProfit     = 0;
    public array $lowStockProducts = [];

    public string $dateFrom = '';
    public string $dateTo   = '';

    public function mount(): void
    {
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->format('Y-m-d');

        $this->monthlyRevenue = Invoice::query()
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('grand_total');

        $this->monthlyPayroll = Payroll::query()
            ->where('status', 'paid')
            ->where('month', $month)
            ->where('year', $year)
            ->sum('net_salary');

        $this->monthlyBookings = Booking::query()
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->count();

        $this->unpaidInvoices = Invoice::query()
            ->where('payment_status', '!=', 'paid')
            ->count();

        $this->topServices = BookingService::query()
            ->join('services', 'services.id', '=', 'booking_services.service_id')
            ->select('services.name', DB::raw('count(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        $this->topEmployees = BookingService::query()
            ->join('employees', 'employees.id', '=', 'booking_services.employee_id')
            ->select('employees.full_name', DB::raw('count(*) as total'))
            ->groupBy('employees.full_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        $this->topCustomers = Booking::query()
            ->join('customers', 'customers.id', '=', 'bookings.customer_id')
            ->select('customers.name', DB::raw('count(*) as total'))
            ->groupBy('customers.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        $this->grossProfit     = $this->monthlyRevenue - $this->monthlyPayroll;

        $this->lowStockProducts = Product::query()
            ->whereColumn('current_stock', '<=', 'alert_stock')
            ->select('name', 'current_stock', 'alert_stock')
            ->get()
            ->toArray();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $from = $this->dateFrom
            ? Carbon::parse($this->dateFrom)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $to = $this->dateTo
            ? Carbon::parse($this->dateTo)->endOfDay()
            : now()->endOfDay();

        $reports = Invoice::with(['customer', 'booking'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->paginate(15);

        $summary = [
            'total_revenue' => Invoice::whereBetween('created_at', [$from, $to])
                ->where('payment_status', 'paid')
                ->sum('grand_total'),

            'total_transactions' => Invoice::whereBetween('created_at', [$from, $to])
                ->count(),

            'total_payroll' => Payroll::whereBetween('created_at', [$from, $to])
                ->sum('net_salary'),

            'active_customers' => Customer::where('is_active', true)->count(),
        ];

        return view('livewire.reports.index', [
            'reports' => $reports,
            'summary' => $summary,
        ])->layout('layouts.app');
    }
}