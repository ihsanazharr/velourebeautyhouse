<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;

class Edit extends Component
{
    public Service $service;

    public $service_category_id;

    public string $name = '';

    public ?string $description = null;

    public int $duration_minutes = 60;

    public float $price = 0;

    public float $commission_rate = 0;

    public bool $requires_booking = true;

    public bool $is_active = true;

    public function mount(Service $service)
    {
        $this->service = $service;

        $this->service_category_id =
            $service->service_category_id;

        $this->name =
            $service->name;

        $this->description =
            $service->description;

        $this->duration_minutes =
            $service->duration_minutes;

        $this->price =
            $service->price;

        $this->commission_rate =
            $service->commission_rate;

        $this->requires_booking =
            $service->requires_booking;

        $this->is_active =
            $service->is_active;
    }

    public function save()
    {
        $validated = $this->validate([
            'service_category_id' => [
                'required',
                'exists:service_categories,id',
            ],

            'name' => [
                'required',
                'max:255',
            ],

            'description' => [
                'nullable',
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'commission_rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'requires_booking' => [
                'boolean',
            ],

            'is_active' => [
                'boolean',
            ],
        ]);

        $this->service->update(
            $validated
        );

        session()->flash(
            'success',
            'Layanan berhasil diperbarui.'
        );

        return redirect()->route(
            'owner.services.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.services.edit',
            [
                'categories' =>
                    ServiceCategory::query()
                        ->orderBy('name')
                        ->get(),
            ]
        )->layout('layouts.app');
    }
}