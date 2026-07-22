<?php

namespace App\Livewire\ServiceCategories;

use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public ServiceCategory $category;

    public string $name = '';

    public bool $is_active = true;

    public function mount(
        ServiceCategory $serviceCategory
    )
    {
        $this->category = $serviceCategory;

        $this->name =
            $serviceCategory->name;

        $this->is_active =
            $serviceCategory->is_active;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'max:255',
            ],

            'is_active' => [
                'boolean',
            ],
        ]);

        $this->category->update([
            'name' => $validated['name'],

            'slug' => Str::slug(
                $validated['name']
            ),

            'is_active' => $validated['is_active'],
        ]);

        session()->flash(
            'success',
            'Kategori berhasil diperbarui.'
        );

        return redirect()->route(
            'owner.service-categories.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.service-categories.edit'
        )->layout('layouts.app');
    }
}
