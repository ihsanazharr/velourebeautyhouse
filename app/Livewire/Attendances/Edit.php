<?php

namespace App\Livewire\Attendances;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public Attendance $attendance;

    public string $employee_id = '';

    public string $attendance_date = '';

    public string $checkin_time = '';

    public string $checkout_time = '';

    public function mount(Attendance $attendance): void
    {
        $this->attendance = $attendance;
        $this->employee_id = $attendance->employee_id;
        $this->attendance_date = $attendance->attendance_date?->toDateString() ?? '';
        $this->checkin_time = $attendance->checkin_time?->format('H:i') ?? '';
        $this->checkout_time = $attendance->checkout_time?->format('H:i') ?? '';
    }

    public function save()
    {
        $validated = $this->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'attendance_date' => ['required', 'date'],
            'checkin_time' => ['required'],
            'checkout_time' => ['required'],
        ]);

        $setting = Setting::first();

        $officeStart = Carbon::parse($validated['attendance_date'] . ' ' . $setting->open_time);
        $officeEnd = Carbon::parse($validated['attendance_date'] . ' ' . $setting->close_time);
        $checkin = Carbon::parse($validated['attendance_date'] . ' ' . $validated['checkin_time']);
        $checkout = Carbon::parse($validated['attendance_date'] . ' ' . $validated['checkout_time']);

        $lateMinutes = max(0, $officeStart->diffInMinutes($checkin, false));
        $latePenalty = $lateMinutes > $setting->late_tolerance_minutes
            ? $setting->late_penalty_amount
            : 0;
        $earlyCheckoutMinutes = max(0, $checkout->diffInMinutes($officeEnd, false));
        $overtimeMinutes = max(0, $officeEnd->diffInMinutes($checkout, false));
        $overtimeAmount = ($overtimeMinutes / 60) * $setting->overtime_rate;

        $this->attendance->update([
            'employee_id' => $validated['employee_id'],
            'attendance_date' => $validated['attendance_date'],
            'checkin_time' => $checkin,
            'checkout_time' => $checkout,
            'late_minutes' => $lateMinutes,
            'late_penalty' => $latePenalty,
            'early_checkout_minutes' => $earlyCheckoutMinutes,
            'early_checkout_penalty' => 0,
            'meal_allowance' => $setting->meal_allowance_amount,
            'overtime_minutes' => $overtimeMinutes,
            'overtime_amount' => $overtimeAmount,
            'status' => 'present',
        ]);

        session()->flash('success', 'Absensi berhasil diperbarui.');

        return redirect()->route('owner.attendances.index');
    }

    public function render()
    {
        return view('livewire.attendances.edit', [
            'employees' => Employee::query()
                ->where('employment_status', 'active')
                ->orderBy('full_name')
                ->get(),
        ])->layout('layouts.app');
    }
}
