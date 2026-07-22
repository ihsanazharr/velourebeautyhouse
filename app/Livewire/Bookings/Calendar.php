<?php

namespace App\Livewire\Bookings;

use App\Models\BookingService;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public string $date;

    public function mount()
    {
        $this->date = today()->toDateString();
    }

    public function render()
    {
        $bookings = BookingService::query()
            ->with([
                'booking.customer',
                'employee',
                'service',
            ])
            ->whereDate(
                'start_time',
                $this->date
            )
            ->orderBy('start_time')
            ->get();

        return view(
            'livewire.bookings.calendar',
            compact('bookings')
        )->layout('layouts.app');
    }
}