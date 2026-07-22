<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
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
        $customers = Customer::query()
            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'name',
                    'ilike',
                    '%' . $this->search . '%'
                )
                ->orWhere(
                    'phone',
                    'ilike',
                    '%' . $this->search . '%'
                )
            )
            ->orderBy('name')
            ->paginate(10);

        return view(
            'livewire.customers.index',
            compact('customers')
        )->layout('layouts.app');
    }
}