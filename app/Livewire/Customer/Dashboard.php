<?php

namespace App\Livewire\Customer;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    private function getCustomer(): Customer
    {
        $user = auth()->user();

        return Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'phone' => '-',
                'email' => $user->email,
                'is_active' => true,
            ]
        );
    }

    public function render()
    {
        $customer = $this->getCustomer();

        // Get bookings with services and invoice
        $bookings = Booking::query()
            ->whereHas('bookingCustomers', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->with(['services.service', 'invoice'])
            ->latest('booking_date')
            ->latest('created_at')
            ->paginate(5);

        // Stats calculation
        $totalBookings = Booking::query()
            ->whereHas('bookingCustomers', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->count();

        $completedBookings = Booking::query()
            ->whereHas('bookingCustomers', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->where('status', 'completed')
            ->count();

        $pendingBookings = Booking::query()
            ->whereHas('bookingCustomers', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->where('status', 'pending')
            ->count();

        return view('livewire.customer.dashboard', [
            'customer' => $customer,
            'bookings' => $bookings,
            'stats' => [
                'total' => $totalBookings,
                'completed' => $completedBookings,
                'pending' => $pendingBookings,
            ]
        ])->layout('layouts.app');
    }
}
