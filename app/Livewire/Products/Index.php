<?php

namespace App\Livewire\Products;

use App\Models\Product;
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
        $products = Product::query()
            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'name',
                    'ilike',
                    '%' . $this->search . '%'
                )
            )
            ->orderBy('name')
            ->paginate(10);

        return view(
            'livewire.products.index',
            compact('products')
        )->layout('layouts.app');
    }
}