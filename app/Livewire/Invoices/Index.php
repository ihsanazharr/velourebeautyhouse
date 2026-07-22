<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view(
            'livewire.invoices.index',
            [
                'invoices' => Invoice::query()
                    ->with([
                        'customer',
                        'booking',
                    ])
                    ->latest()
                    ->get(),
            ]
        )->layout('layouts.app');
    }
}