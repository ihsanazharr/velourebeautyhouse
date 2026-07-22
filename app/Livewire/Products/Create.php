<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class Create extends Component
{
    public string $sku = '';

    public string $name = '';

    public string $category = '';

    public ?string $description = null;

    public string $unit = 'pcs';

    public int $current_stock = 0;

    public int $minimum_stock = 0;

    public int $alert_stock = 0;

    public float $cost_price = 0;

    public float $selling_price = 0;

    public ?string $supplier = null;

    public bool $is_active = true;

    public function mount(): void
    {
        $this->sku =
            'PRD-' .
            now()->format('Ymd') .
            '-' .
            str_pad(
                Product::count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );
    }

    public function save()
    {
        $validated = $this->validate([
            'sku' => [
                'required',
                'max:255',
                'unique:products,sku'
            ],

            'name' => [
                'required',
                'max:255'
            ],

            'category' => [
                'required',
                'max:255'
            ],

            'description' => [
                'nullable'
            ],

            'unit' => [
                'required',
                'max:255'
            ],

            'current_stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'alert_stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'cost_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'supplier' => [
                'nullable',
                'max:255'
            ],

            'is_active' => [
                'boolean'
            ],
        ]);

        Product::create($validated);

        session()->flash(
            'success',
            'Produk berhasil ditambahkan.'
        );

        return redirect()->route(
            'owner.products.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.products.create'
        )->layout('layouts.app');
    }
}