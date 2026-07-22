<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class Edit extends Component
{
    public Product $product;

    public string  $sku           = '';
    public string  $name          = '';
    public string  $category      = '';
    public ?string $description   = null;
    public string  $unit          = 'pcs';
    public int     $current_stock = 0;
    public int     $minimum_stock = 0;
    public int     $alert_stock   = 0;
    public float   $cost_price    = 0;
    public float   $selling_price = 0;
    public ?string $supplier      = null;
    public bool    $is_active     = true;

    public function mount(Product $product): void
    {
        $this->product       = $product;
        $this->sku           = $product->sku;
        $this->name          = $product->name;
        $this->category      = $product->category;
        $this->description   = $product->description;
        $this->unit          = $product->unit;
        $this->current_stock = (int) $product->current_stock;
        $this->minimum_stock = (int) $product->minimum_stock;
        $this->alert_stock   = (int) $product->alert_stock;
        $this->cost_price    = (float) $product->cost_price;
        $this->selling_price = (float) $product->selling_price;
        $this->supplier      = $product->supplier;
        $this->is_active     = (bool) $product->is_active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'sku'           => ['required', 'max:255', 'unique:products,sku,' . $this->product->id],
            'name'          => ['required', 'max:255'],
            'category'      => ['required', 'max:255'],
            'description'   => ['nullable'],
            'unit'          => ['required', 'max:255'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'alert_stock'   => ['required', 'integer', 'min:0'],
            'cost_price'    => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'supplier'      => ['nullable', 'max:255'],
            'is_active'     => ['boolean'],
        ]);

        $this->product->update($validated);

        session()->flash('success', 'Produk berhasil diperbarui.');

        $this->redirect(route('owner.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.edit')->layout('layouts.app');
    }
}