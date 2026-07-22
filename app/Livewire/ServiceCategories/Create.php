<?php

namespace App\Livewire\ServiceCategories;

use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public bool $is_active = true;

    public function save()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'max:255',
                'unique:service_categories,name',
            ],

            'is_active' => [
                'boolean',
            ],
        ]);

        ServiceCategory::create([
            'name' => $validated['name'],

            'slug' => Str::slug(
                $validated['name']
            ),

            'is_active' => $validated['is_active'],
        ]);

        session()->flash(
            'success',
            'Kategori berhasil ditambahkan.'
        );

        return redirect()->route(
            'owner.service-categories.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.service-categories.create'
        )->layout('layouts.app');
    }
}