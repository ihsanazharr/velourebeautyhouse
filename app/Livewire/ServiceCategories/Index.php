<?php

namespace App\Livewire\ServiceCategories;

use App\Models\ServiceCategory;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view(
            'livewire.service-categories.index',
            [
                'categories' =>
                    ServiceCategory::query()
                        ->orderBy('name')
                        ->get(),
            ]
        )->layout('layouts.app');
    }
}