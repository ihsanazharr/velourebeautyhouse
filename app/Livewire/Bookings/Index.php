<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $bookings = Booking::query()
            ->with('customer')
            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'booking_number',
                    'ilike',
                    '%' . $this->search . '%'
                )
            )
            ->latest('booking_date')
            ->paginate(10);

        return view(
            'livewire.bookings.index',
            compact('bookings')
        )->layout('layouts.app');
    }

    public function generateInvoice(
    string $bookingId
)
{
    $booking = Booking::with(
        'customer',
        'invoice'
    )->findOrFail(
        $bookingId
    );

    if ($booking->invoice) {

        session()->flash(
            'error',
            'Invoice sudah ada.'
        );

        return;
    }

    DB::transaction(function () use ($booking) {

        Invoice::create([
            'invoice_number' =>
                'INV-' .
                now()->format(
                    'YmdHis'
                ),

            'booking_id' =>
                $booking->id,

            'customer_id' =>
                $booking->customer_id,

            'subtotal' =>
                $booking->total_amount,

            'discount' => 0,

            'grand_total' =>
                $booking->total_amount,

            'payment_status' =>
                $booking->status === 'completed' ? 'paid' : 'unpaid',

            'invoice_sent' =>
                false,
        ]);
    });

    session()->flash(
        'success',
        'Invoice berhasil dibuat.'
    );
}
}