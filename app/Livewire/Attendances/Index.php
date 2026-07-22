<?php

namespace App\Livewire\Attendances;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public ?string $employee_id = null;
    public ?string $capturedImage = null;

    private function saveCapturedImage(): string
    {
        $image = preg_replace(
            '#^data:image/\w+;base64,#i',
            '',
            $this->capturedImage
        );

        $image = str_replace(
            ' ',
            '+',
            $image
        );

        $fileName =
            'attendances/'
            . uniqid()
            . '.jpg';

        Storage::disk('public')->put(
            $fileName,
            base64_decode($image)
        );

        return $fileName;
    }

    public function clockIn()
    {
        $this->validate([
            'employee_id' => ['required'],
            'capturedImage' => ['required'],
        ]);

        $attendance = Attendance::query()
            ->where('employee_id', $this->employee_id)
            ->whereDate('attendance_date', today())
            ->first();

        if ($attendance) {

            session()->flash(
                'error',
                'Karyawan sudah check in hari ini.'
            );

            return;
        }

        $setting = Setting::first();

        $path = $this->saveCapturedImage();

        $startTime = Carbon::parse(
            today()->format('Y-m-d')
            . ' '
            . $setting->open_time
        );

        $checkinTime = now();

        $lateMinutes = 0;
        $latePenalty = 0;

        $toleranceLimit = $startTime
            ->copy()
            ->addMinutes(
                $setting->late_tolerance_minutes
            );

        if ($checkinTime->gt($toleranceLimit)) {

            $lateMinutes =
                $checkinTime->diffInMinutes(
                    $startTime
                );

            $lateBlocks =
                ceil(
                    $lateMinutes / 10
                );

            $latePenalty =
                $lateBlocks
                * $setting->late_penalty_amount;
        }

        Attendance::create([
            'employee_id' => $this->employee_id,
            'attendance_date' => today(),
            'checkin_time' => now(),
            'checkin_photo' => $path,
            'late_minutes' => $lateMinutes,
            'late_penalty' => $latePenalty,
            'status' => 'present',
        ]);

        $this->reset('capturedImage');

        session()->flash(
            'success',
            'Check In berhasil.'
        );
    }

    public function clockOut()
    {
        $this->validate([
            'employee_id' => ['required'],
            'capturedImage' => ['required'],
        ]);

        $attendance = Attendance::query()
            ->where('employee_id', $this->employee_id)
            ->whereDate('attendance_date', today())
            ->first();

        if (! $attendance) {

            session()->flash(
                'error',
                'Belum melakukan check in.'
            );

            return;
        }

        if ($attendance->checkout_time) {

            session()->flash(
                'error',
                'Sudah check out.'
            );

            return;
        }

        $setting = Setting::first();

        $path = $this->saveCapturedImage();

        $closeTime = Carbon::parse(
            today()->format('Y-m-d')
            . ' '
            . $setting->close_time
        );

        $now = now();

        $overtimeMinutes = 0;
        $overtimeAmount = 0;

        if ($now->gt($closeTime)) {

            $overtimeMinutes =
                $closeTime->diffInMinutes($now);

            $overtimeAmount =
                ($overtimeMinutes / 60)
                * $setting->overtime_rate;
        }

        $attendance->update([
            'checkout_time' => now(),
            'checkout_photo' => $path,
            'meal_allowance' =>
                $setting->meal_allowance_amount,
            'overtime_minutes' =>
                $overtimeMinutes,
            'overtime_amount' =>
                $overtimeAmount,
        ]);

        $this->reset('capturedImage');

        session()->flash(
            'success',
            'Check Out berhasil.'
        );
    }

    public function render()
    {
        $todayAttendance = null;

        if ($this->employee_id) {

            $todayAttendance =
                Attendance::query()
                ->where(
                    'employee_id',
                    $this->employee_id
                )
                ->whereDate(
                    'attendance_date',
                    today()
                )
                ->first();
        }

        return view(
            'livewire.attendances.index',
            [
                'employees' =>
                    Employee::query()
                    ->where(
                        'employment_status',
                        'active'
                    )
                    ->orderBy('full_name')
                    ->get(),

                'todayAttendance' =>
                    $todayAttendance,

                'attendances' =>
                    Attendance::query()
                    ->with('employee')
                    ->latest('attendance_date')
                    ->latest('id')
                    ->paginate(15),
            ]
        )->layout('layouts.app');
    }
}