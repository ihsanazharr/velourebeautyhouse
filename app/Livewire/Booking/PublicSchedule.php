<?php

namespace App\Livewire\Booking;

use App\Models\BookingService;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class PublicSchedule extends Component
{
    /**
     * Tanggal yang ditampilkan. Selalu "hari ini" berdasarkan zona
     * waktu Asia/Jakarta (GMT+7), sesuai kebutuhan real-time.
     */
    public string $date;

    /**
     * Jam operasional salon (format 24 jam, "H:i").
     * Sesuaikan dengan jam buka/tutup toko yang sebenarnya.
     */
    public string $openTime = '11:00';
    public string $closeTime = '20:00';

    /**
     * Interval antar slot jadwal (menit).
     */
    public int $slotMinutes = 30;

    protected string $timezone = 'Asia/Jakarta';

    public ?int $selectedCategoryId = null;

    public function mount(): void
    {
        $this->date = Carbon::now($this->timezone)->toDateString();
    }

    /**
     * Dipanggil saat pengunjung mengklik salah satu jam yang tersedia.
     * Tamu (belum login) akan diarahkan ke halaman login terlebih
     * dahulu sebelum bisa melanjutkan proses booking.
     */
    public function selectSlot(string $time): mixed
    {
        session()->put('intended_booking', [
            'date' => $this->date,
            'time' => $time,
        ]);

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()?->hasRole('customer')) {
            return redirect()->route('customer.booking.create');
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $now = Carbon::now($this->timezone);

        $categories = \App\Models\ServiceCategory::query()
            ->where('is_active', true)
            ->whereHas('services', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $services = Service::query()
            ->where('is_active', true)
            ->when($this->selectedCategoryId, fn($q) => $q->where('service_category_id', $this->selectedCategoryId))
            ->with('category')
            ->orderBy('name')
            ->get();

        // Rentang waktu yang sudah dibooking hari ini (dihitung presisi berdasarkan GMT+7 Jakarta).
        $startOfDay = Carbon::parse($this->date)->startOfDay();
        $endOfDay = Carbon::parse($this->date)->endOfDay();

        $bookedRanges = BookingService::query()
            ->whereBetween('start_time', [$startOfDay, $endOfDay])
            ->orderBy('start_time')
            ->get(['start_time', 'end_time'])
            ->map(fn ($booking) => [
                'start' => Carbon::parse($booking->start_time),
                'end' => Carbon::parse($booking->end_time),
            ]);

        $open = Carbon::parse("{$this->date} {$this->openTime}");
        $close = Carbon::parse("{$this->date} {$this->closeTime}");

        $slots = collect();
        $cursor = $open->copy();

        while ($cursor->lt($close)) {
            $slotEnd = $cursor->copy()->addMinutes($this->slotMinutes);

            $isPast = $cursor->lt($now);

            $isBooked = $bookedRanges->contains(
                fn ($range) => $cursor->lt($range['end']) && $slotEnd->gt($range['start'])
            );

            $slots->push([
                'time' => $cursor->format('H:i'),
                'available' => ! $isPast && ! $isBooked,
            ]);

            $cursor->addMinutes($this->slotMinutes);
        }

        $settings = \App\Models\Setting::first();
        $isBookingEnabled = $settings?->booking_enabled ?? true;

        return view('livewire.bookings.public-schedule', [
            'services' => $services,
            'categories' => $categories,
            'slots' => $slots,
            'today' => $now,
            'isBookingEnabled' => $isBookingEnabled,
            'whatsappNumber' => $settings?->whatsapp_number ?? '',
        ]);
    }
}
