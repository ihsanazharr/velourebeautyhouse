<?php

namespace App\Livewire\EmployeeAdjustments;

use App\Models\Employee;
use App\Models\EmployeeAdjustment;
use Livewire\Component;

class Create extends Component
{
    public string $employee_id = '';

    public string $adjustment_date = '';

    public string $type = 'bonus';

    public float $amount = 0;

    public ?string $description = null;

    public function save()
    {
        $validated = $this->validate([
            'employee_id' => [
                'required',
                'exists:employees,id'
            ],

            'adjustment_date' => [
                'required',
                'date'
            ],

            'type' => [
                'required',
                'in:commission,bonus,deduction'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'description' => [
                'nullable'
            ],
        ]);

        EmployeeAdjustment::create([
            'employee_id' => $validated['employee_id'],

            'adjustment_date' => $validated['adjustment_date'],

            'type' => $validated['type'],

            'amount' => $validated['amount'],

            'description' => $validated['description'],

            'approved_by' => null,

            'approved_at' => null,
        ]);

        session()->flash(
            'success',
            'Adjustment berhasil ditambahkan.'
        );

        return redirect()->route(
            'owner.adjustments.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.employee-adjustments.create',
            [
                'employees' => Employee::query()
                    ->where(
                        'employment_status',
                        'active'
                    )
                    ->orderBy('full_name')
                    ->get()
            ]
        )->layout('layouts.app');
    }
}