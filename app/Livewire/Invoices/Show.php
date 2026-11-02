<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class Show extends Component
{
    public Invoice $invoice;

    // Payment fields
    public string $payment_method = 'Cash';
    public $cash_received = 0;
    public $cash_change = 0;
    public bool $show_customer_name = true;

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

        $this->show_customer_name = (bool) ($this->invoice->show_customer_name ?? ($this->invoice->customer_id ? true : false));

        // Default cash received to grand total
        $this->cash_received = (float) $this->invoice->grand_total;
        $this->calculateChange();
    }

    public function updatedShowCustomerName(): void
    {
        $this->invoice->update([
            'show_customer_name' => $this->show_customer_name,
        ]);
    }

    public function updatedPaymentMethod(): void
    {
        if ($this->payment_method !== 'Cash') {
            $this->cash_received = (float) $this->invoice->grand_total;
        }
        $this->calculateChange();
    }

    public function updatedCashReceived(): void
    {
        $this->calculateChange();
    }

    private function calculateChange(): void
    {
        $received = (float) ($this->cash_received ?: 0);
        $total = (float) $this->invoice->grand_total;
        $this->cash_change = max(0, $received - $total);
    }

    public function markPaid()
    {
        $this->validate([
            'payment_method' => ['required', 'in:Cash,QRIS,Bank Transfer,Debit'],
            'cash_received' => ['required', 'numeric', 'min:0'],
        ]);

        $received = $this->payment_method === 'Cash' ? (float) $this->cash_received : (float) $this->invoice->grand_total;
        $change = $this->payment_method === 'Cash' ? max(0, $received - (float) $this->invoice->grand_total) : 0;

        // If the booking is still pending or confirmed, make sure it is completed upon payment
        if ($this->invoice->booking && $this->invoice->booking->status !== 'completed') {
            $this->invoice->booking->update(['status' => 'completed']);
        }

        $this->invoice->update([
            'payment_status' => 'paid',
            'payment_method' => $this->payment_method,
            'cash_received' => $received,
            'cash_change' => $change,
            'show_customer_name' => $this->show_customer_name,
        ]);

        session()->flash('success', 'Transaksi berhasil dan invoice telah ditandai LUNAS.');

        $routePrefix = auth()->user()?->hasRole('kasir') ? 'kasir' : 'owner';

        return redirect()->route($routePrefix . '.invoices.success', $this->invoice->id);
    }

    public function render()
    {
        return view('livewire.invoices.show')->layout('layouts.app');
    }
}