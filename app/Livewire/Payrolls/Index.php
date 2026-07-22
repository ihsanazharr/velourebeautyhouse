<?php

namespace App\Livewire\Payrolls;

use App\Models\Payroll;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view(
            'livewire.payrolls.index',
            [
                'payrolls' => Payroll::with(
                    'employee'
                )
                ->latest()
                ->get()
            ]
        )->layout('layouts.app');
    }
}