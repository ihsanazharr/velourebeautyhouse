<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;

class Create extends Component
{
    public $service_category_id;

    public string $name = '';

    public ?string $description = null;

    public int $duration_minutes = 60;

    public float $price = 0;

    public float $commission_rate = 0;

    public bool $requires_booking = true;

    public bool $is_active = true;

    public function save()
    {
        $validated = $this->validate([
            'service_category_id' => [
                'required',
                'exists:service_categories,id'
            ],

            'name' => [
                'required',
                'max:255'
            ],

            'description' => [
                'nullable'
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:1'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'commission_rate' => [
                'required',
                'numeric',
                'min:0'
            ],

            'requires_booking' => [
                'boolean'
            ],

            'is_active' => [
                'boolean'
            ],
        ]);

        Service::create($validated);

        session()->flash(
            'success',
            'Layanan berhasil ditambahkan.'
        );

        return redirect()->route(
            'owner.services.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.services.create',
            [
                'categories' => ServiceCategory::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get()
            ]
        )->layout('layouts.app');
    }
}