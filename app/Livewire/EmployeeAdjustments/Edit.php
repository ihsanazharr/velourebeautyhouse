<?php

namespace App\Livewire\EmployeeAdjustments;

use App\Models\Employee;
use App\Models\EmployeeAdjustment;
use Livewire\Component;

class Edit extends Component
{
    public EmployeeAdjustment $adjustment;

    public string $employee_id = '';

    public string $adjustment_date = '';

    public string $type = 'bonus';

    public float $amount = 0;

    public ?string $description = null;

    public function mount(EmployeeAdjustment $adjustment): void
    {
        $this->adjustment = $adjustment;
        $this->employee_id = $adjustment->employee_id;
        $this->adjustment_date = $adjustment->adjustment_date?->toDateString() ?? '';
        $this->type = $adjustment->type;
        $this->amount = (float) $adjustment->amount;
        $this->description = $adjustment->description;
    }

    public function save()
    {
        $validated = $this->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'adjustment_date' => ['required', 'date'],
            'type' => ['required', 'in:commission,bonus,deduction'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $this->adjustment->update($validated);

        session()->flash('success', 'Adjustment berhasil diperbarui.');

        return redirect()->route('owner.adjustments.index');
    }

    public function render()
    {
        return view(
            'livewire.employee-adjustments.edit',
            [
                'employees' => Employee::query()
                    ->where('employment_status', 'active')
                    ->orderBy('full_name')
                    ->get(),
            ]
        )->layout('layouts.app');
    }
}
