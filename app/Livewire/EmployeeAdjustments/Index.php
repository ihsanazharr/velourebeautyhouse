<?php

namespace App\Livewire\EmployeeAdjustments;

use App\Models\EmployeeAdjustment;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view(
            'livewire.employee-adjustments.index',
            [
                'adjustments' => EmployeeAdjustment::with(
                    'employee'
                )
                ->latest('adjustment_date')
                ->get(),
            ]
        )->layout('layouts.app');
    }
}