<?php

namespace App\Livewire\Services;

use App\Models\Service;
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
        $services = Service::query()
            ->with('category')
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
            compact('services')
        )->layout('layouts.app');
    }
}