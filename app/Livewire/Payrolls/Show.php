<?php

namespace App\Livewire\Payrolls;

use App\Models\Payroll;
use Livewire\Component;

class Show extends Component
{
    public Payroll $payroll;

    public function mount(
        Payroll $payroll
    )
    {
        $this->payroll = $payroll->load(
            [
                'employee',
                'details',
            ]
        );
    }

    public function approve()
    {
        if (
            $this->payroll->status !==
            'draft'
        ) {
            return;
        }

        $this->payroll->update([
            'status' => 'approved',
        ]);

        $this->payroll->refresh();
    }

    public function markAsPaid()
    {
        if (
            $this->payroll->status !==
            'approved'
        ) {
            return;
        }

        $this->payroll->update([
            'status' => 'paid',
        ]);

        $this->payroll->refresh();
    }

    public function render()
    {
        return view(
            'livewire.payrolls.show'
        )->layout('layouts.app');
    }
}