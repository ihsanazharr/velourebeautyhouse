<?php

namespace App\Livewire\Owner;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    public string $company_name = '';
    public string $address = '';
    public string $phone = '';
    public string $whatsapp_number = '';
    public ?string $email = null;
    public string $open_time = '11:00';
    public string $close_time = '20:00';
    public int $late_tolerance_minutes = 10;
    public float $late_penalty_amount = 10000;
    public float $overtime_rate = 15000;
    public float $meal_allowance_amount = 15000;
    public bool $booking_enabled = true;

    // Image Uploads
    public $hero_image_file;
    public $qris_image_file;

    // Current File Paths
    public ?string $current_hero_image = null;
    public ?string $current_qris_image = null;

    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->company_name = $setting->company_name;
            $this->address = $setting->address;
            $this->phone = $setting->phone;
            $this->whatsapp_number = $setting->whatsapp_number;
            $this->email = $setting->email;
            $this->open_time = substr($setting->open_time, 0, 5); // Format HH:MM
            $this->close_time = substr($setting->close_time, 0, 5); // Format HH:MM
            $this->late_tolerance_minutes = $setting->late_tolerance_minutes;
            $this->late_penalty_amount = $setting->late_penalty_amount;
            $this->overtime_rate = $setting->overtime_rate;
            $this->meal_allowance_amount = $setting->meal_allowance_amount;
            $this->booking_enabled = (bool) ($setting->booking_enabled ?? true);
            $this->current_hero_image = $setting->hero_image;
            $this->current_qris_image = $setting->qris_image;
        }
    }

    public function deleteHeroImage(): void
    {
        $setting = Setting::first();
        if ($setting && $setting->hero_image) {
            Storage::disk('public')->delete($setting->hero_image);
            $setting->update(['hero_image' => null]);
            $this->current_hero_image = null;
            session()->flash('success', 'Gambar banner landing page berhasil dihapus.');
        }
    }

    public function deleteQrisImage(): void
    {
        $setting = Setting::first();
        if ($setting && $setting->qris_image) {
            Storage::disk('public')->delete($setting->qris_image);
            $setting->update(['qris_image' => null]);
            $this->current_qris_image = null;
            session()->flash('success', 'Gambar QRIS pembayaran berhasil dihapus.');
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i'],
            'late_tolerance_minutes' => ['required', 'integer', 'min:0'],
            'late_penalty_amount' => ['required', 'numeric', 'min:0'],
            'overtime_rate' => ['required', 'numeric', 'min:0'],
            'meal_allowance_amount' => ['required', 'numeric', 'min:0'],
            'booking_enabled' => ['required', 'boolean'],
            'hero_image_file' => ['nullable', 'image', 'max:4096'], // Max 4MB
            'qris_image_file' => ['nullable', 'image', 'max:4096'], // Max 4MB
        ]);

        $setting = Setting::first() ?: new Setting();

        // Handle Hero Image Upload
        if ($this->hero_image_file) {
            if ($setting->hero_image) {
                Storage::disk('public')->delete($setting->hero_image);
            }
            $validated['hero_image'] = $this->hero_image_file->store('settings', 'public');
        }

        // Handle QRIS Image Upload
        if ($this->qris_image_file) {
            if ($setting->qris_image) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            $validated['qris_image'] = $this->qris_image_file->store('settings', 'public');
        }

        // Remove files from validation array as they are handled manually
        unset($validated['hero_image_file']);
        unset($validated['qris_image_file']);

        $setting->fill($validated);
        $setting->save();

        session()->flash('success', 'Pengaturan sistem berhasil diperbarui.');

        return redirect()->route('owner.settings.edit');
    }

    public function render()
    {
        return view('livewire.owner.settings')->layout('layouts.app');
    }
}
