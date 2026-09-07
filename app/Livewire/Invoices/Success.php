<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class Success extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice->load([
            'customer',
            'booking.services.service',
            'booking.services.employee',
            'booking.products.product',
            'booking.paymentProofs',
            'booking.bookingCustomers.customer',
        ]);
    }

    public function render()
    {
        return view('livewire.invoices.success')->layout('layouts.app');
    }
}
