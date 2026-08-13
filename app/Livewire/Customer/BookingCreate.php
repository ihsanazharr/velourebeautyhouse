<?php

namespace App\Livewire\Customer;

use App\Models\Booking;
use App\Models\BookingCustomer;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\PaymentProof;
use App\Models\Service;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class BookingCreate extends Component
{
    use WithFileUploads;

    public string $date = '';

    public string $time = '';

    public int $peopleCount = 1;

    public array $participants = [
        [
            'name' => '',
            'service_ids' => [],
        ],
    ];

    public ?string $notes = null;

    public ?TemporaryUploadedFile $proofImage = null;

    protected string $timezone = 'Asia/Jakarta';

    public function mount(): void
    {
        $settings = Setting::first();
        if ($settings && !$settings->booking_enabled) {
            $this->redirect(route('welcome'));
            return;
        }

        $intendedBooking = session('intended_booking', []);

        $this->date = $intendedBooking['date'] ?? Carbon::now($this->timezone)->toDateString();
        $this->time = $intendedBooking['time'] ?? '';
        $this->participants[0]['name'] = auth()->user()?->name ?? '';
    }

    public function updatedPeopleCount(): void
    {
        $this->peopleCount = min(6, max(1, $this->peopleCount));

        while (count($this->participants) < $this->peopleCount) {
            $this->participants[] = [
                'name' => '',
                'service_ids' => [],
            ];
        }

        $this->participants = array_slice($this->participants, 0, $this->peopleCount);

        if (($this->participants[0]['name'] ?? '') === '') {
            $this->participants[0]['name'] = auth()->user()?->name ?? '';
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'peopleCount' => ['required', 'integer', 'min:1', 'max:6'],
            'participants' => ['required', 'array', 'min:1'],
            'participants.*.name' => ['required', 'string', 'max:120'],
            'participants.*.service_ids' => ['required', 'array', 'min:1'],
            'participants.*.service_ids.*' => ['required', 'integer', 'exists:services,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'proofImage' => ['required', 'image', 'max:4096'],
        ]);

        $services = Service::query()
            ->where('is_active', true)
            ->whereIn('id', $this->selectedServiceIds())
            ->get()
            ->keyBy('id');

        if ($services->isEmpty()) {
            $this->addError('participants', 'Pilih minimal satu layanan.');

            return null;
        }

        $start = Carbon::parse("{$validated['date']} {$validated['time']}", $this->timezone);
        $end = $this->bookingEnd($start, $services);

        $hasConflict = BookingService::query()
            ->where(function ($query) use ($start, $end) {
                $query
                    ->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($hasConflict) {
            $this->addError('time', 'Jam ini baru saja terisi. Silakan pilih jadwal lain dari halaman utama.');

            return null;
        }

        $proofPath = $this->proofImage->store('payment-proofs', 'public');

        DB::transaction(function () use ($validated, $services, $start, $end, $proofPath) {
            $customer = $this->customer();
            $summary = $this->participantSummary($services);
            $totalAmount = $this->totalAmount($services);

            $booking = Booking::create([
                'booking_number' => 'BK-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'customer_id' => $customer->id,
                'booking_date' => $validated['date'],
                'booking_start' => $start,
                'booking_end' => $end,
                'dp_amount' => 0,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => trim(
                    "Booking customer online\n" .
                    "Jumlah orang: {$validated['peopleCount']}\n\n" .
                    $summary .
                    ($validated['notes'] ? "\n\nCatatan customer:\n{$validated['notes']}" : '')
                ),
            ]);

            BookingCustomer::create([
                'booking_id' => $booking->id,
                'customer_id' => $customer->id,
            ]);

            foreach ($validated['participants'] as $participant) {
                $serviceStart = $start->copy();

                foreach (array_unique($participant['service_ids']) as $serviceId) {
                    $service = $services->get((int) $serviceId);

                    if (! $service) {
                        continue;
                    }

                    $serviceEnd = $serviceStart->copy()->addMinutes($service->duration_minutes);

                    BookingService::create([
                        'booking_id' => $booking->id,
                        'service_id' => $service->id,
                        'employee_id' => null,
                        'price' => $service->price,
                        'commission_percentage' => 0,
                        'commission_amount' => 0,
                        'duration_minutes' => $service->duration_minutes,
                        'start_time' => $serviceStart,
                        'end_time' => $serviceEnd,
                    ]);

                    $serviceStart = $serviceEnd;
                }
            }

            PaymentProof::create([
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'proof_image' => $proofPath,
                'status' => 'pending',
            ]);
        });

        session()->forget('intended_booking');
        session()->flash('success', 'Booking berhasil dikirim. Tim Veloure akan mengonfirmasi jadwal Anda.');

        return redirect()->route('customer.booking.create');
    }

    private function customer(): Customer
    {
        $user = auth()->user();

        return Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'phone' => '-',
                'email' => $user->email,
                'is_active' => true,
            ]
        );
    }

    private function selectedServiceIds(): array
    {
        return collect($this->participants)
            ->flatMap(fn (array $participant) => $participant['service_ids'] ?? [])
            ->map(fn ($serviceId) => (int) $serviceId)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function bookingEnd(Carbon $start, Collection $services): Carbon
    {
        $longestDuration = collect($this->participants)
            ->map(function (array $participant) use ($services) {
                return collect($participant['service_ids'] ?? [])
                    ->unique()
                    ->sum(fn ($serviceId) => $services->get((int) $serviceId)?->duration_minutes ?? 0);
            })
            ->max() ?: 30;

        return $start->copy()->addMinutes($longestDuration);
    }

    private function totalAmount(Collection $services): float
    {
        return collect($this->participants)
            ->flatMap(fn (array $participant) => $participant['service_ids'] ?? [])
            ->sum(fn ($serviceId) => (float) ($services->get((int) $serviceId)?->price ?? 0));
    }

    private function participantSummary(Collection $services): string
    {
        return collect($this->participants)
            ->map(function (array $participant, int $index) use ($services) {
                $serviceNames = collect($participant['service_ids'] ?? [])
                    ->map(fn ($serviceId) => $services->get((int) $serviceId)?->name)
                    ->filter()
                    ->implode(', ');

                return ($index + 1) . '. ' . $participant['name'] . ': ' . $serviceNames;
            })
            ->implode("\n");
    }

    public function render()
    {
        $services = Service::query()
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        $setting = Setting::query()->first();

        return view('livewire.customer.booking-create', [
            'services' => $services,
            'selectedTotal' => $this->totalAmount($services->keyBy('id')),
            'qrisImage' => $setting?->qris_image,
        ])->layout('layouts.app');
    }
}
