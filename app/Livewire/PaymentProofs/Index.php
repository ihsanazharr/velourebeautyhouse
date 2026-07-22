<?php

namespace App\Livewire\PaymentProofs;

use App\Models\PaymentProof;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view(
            'livewire.payment-proofs.index',
            [
                'paymentProofs' =>
                    PaymentProof::query()
                        ->with([
                            'booking.customer'
                        ])
                        ->latest()
                        ->get(),
            ]
        )->layout('layouts.app');
    }
}