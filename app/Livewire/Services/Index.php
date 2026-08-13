<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $selectedCategoryId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategoryId(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $service = Service::findOrFail($id);
        $service->update([
            'is_active' => !$service->is_active
        ]);

        session()->flash('success', 'Status aktif layanan "' . $service->name . '" berhasil diperbarui.');
    }

    public function render()
    {
        $categories = \App\Models\ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $services = Service::query()
            ->with('category')
            ->when($this->selectedCategoryId, fn($q) => $q->where('service_category_id', $this->selectedCategoryId))
            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'name',
                    'like',
                    '%' . $this->search . '%'
                )
            )
            ->orderBy('name')
            ->paginate(10);

        return view(
            'livewire.services.index',
            compact('services', 'categories')
        )->layout('layouts.app');
    }
}