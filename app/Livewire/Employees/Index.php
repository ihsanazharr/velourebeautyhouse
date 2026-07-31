<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with('user.roles')
            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'full_name',
                    'like',
                    '%' . $this->search . '%'
                )
            )
            ->orderBy('full_name')
            ->paginate(10);

        return view(
            'livewire.employees.index',
            compact('employees')
        )->layout('layouts.app');
    }
}