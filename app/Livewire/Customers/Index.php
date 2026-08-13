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
                    'like',
                    '%' . $this->search . '%'
                )
                ->orWhere(
                    'phone',
                    'like',
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

    public function delete(string $id): void
    {
        $customer = Customer::findOrFail($id);

        if ($customer->user_id) {
            $user = \App\Models\User::find($customer->user_id);
            if ($user) {
                $user->delete();
            }
        }

        $customer->delete();
        session()->flash('success', 'Customer berhasil dihapus.');
    }
}