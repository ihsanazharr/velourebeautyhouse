<?php

namespace App\Livewire\Payrolls;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeAdjustment;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\BookingService;

class Generate extends Component
{
    public string $employee_id = '';

    public int $month;

    public int $year;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function generate()
    {
        $validated = $this->validate([
            'employee_id' => [
                'required',
                'exists:employees,id'
            ],

            'month' => [
                'required',
                'integer',
                'between:1,12'
            ],

            'year' => [
                'required',
                'integer'
            ],
        ]);

        $exists = Payroll::where(
            'employee_id',
            $validated['employee_id']
        )
        ->where(
            'month',
            $validated['month']
        )
        ->where(
            'year',
            $validated['year']
        )
        ->exists();

        if ($exists) {

            $this->addError(
                'employee_id',
                'Payroll bulan ini sudah pernah dibuat.'
            );

            return;
        }

        DB::transaction(function () use ($validated) {

            $employee = Employee::findOrFail(
                $validated['employee_id']
            );

            $attendances = Attendance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->whereMonth(
                    'attendance_date',
                    $validated['month']
                )
                ->whereYear(
                    'attendance_date',
                    $validated['year']
                )
                ->get();

            $adjustments = EmployeeAdjustment::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->whereMonth(
                    'adjustment_date',
                    $validated['month']
                )
                ->whereYear(
                    'adjustment_date',
                    $validated['year']
                )
                ->get();

            $mealAllowanceTotal =
                $attendances->sum(
                    'meal_allowance'
                );

            $overtimeTotal =
                $attendances->sum(
                    'overtime_amount'
                );

            $latePenaltyTotal =
                $attendances->sum(
                    'late_penalty'
                );

            $earlyCheckoutPenaltyTotal =
                $attendances->sum(
                    'early_checkout_penalty'
                );

            $bonusTotal =
                $adjustments
                    ->where(
                        'type',
                        'bonus'
                    )
                    ->sum(
                        'amount'
                    );

            $manualCommissionTotal =
                $adjustments
                    ->where(
                        'type',
                        'commission'
                    )
                    ->sum(
                        'amount'
                    );

            $commissionTotal =
                BookingService::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->whereMonth(
                    'start_time',
                    $validated['month']
                )
                ->whereYear(
                    'start_time',
                    $validated['year']
                )
                ->sum(
                    'commission_amount'
                );

            $deductionTotal =
                $adjustments
                    ->where(
                        'type',
                        'deduction'
                    )
                    ->sum(
                        'amount'
                    );

            $adjustmentTotal =
                $bonusTotal
                +
                $manualCommissionTotal
                -
                $deductionTotal;

            $grossSalary =
                $employee->basic_salary
                +
                $mealAllowanceTotal
                +
                $overtimeTotal
                +
                $commissionTotal
                +
                $manualCommissionTotal
                +
                $bonusTotal;

            $netSalary =
                $grossSalary
                -
                $latePenaltyTotal
                -
                $earlyCheckoutPenaltyTotal
                -
                $deductionTotal;

            $payroll = Payroll::create([
                'employee_id' => $employee->id,

                'month' => $validated['month'],

                'year' => $validated['year'],

                'basic_salary' =>
                    $employee->basic_salary,

                'meal_allowance_total' =>
                    $mealAllowanceTotal,

                'overtime_total' =>
                    $overtimeTotal,

                'commission_total' =>
                    $commissionTotal,

                'adjustment_total' =>
                    $adjustmentTotal,

                'late_penalty_total' =>
                    $latePenaltyTotal,

                'early_checkout_penalty_total' =>
                    $earlyCheckoutPenaltyTotal,

                'gross_salary' =>
                    $grossSalary,

                'net_salary' =>
                    $netSalary,

                'generated_by' => null,

                'generated_at' => now(),

                'status' => 'draft',
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Gaji Pokok',
                'type' => 'earning',
                'amount' => $employee->basic_salary,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Uang Makan',
                'type' => 'earning',
                'amount' => $mealAllowanceTotal,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Lembur',
                'type' => 'earning',
                'amount' => $overtimeTotal,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Komisi Booking',
                'type' => 'earning',
                'amount' => $commissionTotal,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Komisi Tambahan',
                'type' => 'earning',
                'amount' => $manualCommissionTotal,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Adjustment',
                'type' => 'earning',
                'amount' => $adjustmentTotal,
            ]);

            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'description' => 'Potongan Telat',
                'type' => 'deduction',
                'amount' => $latePenaltyTotal,
            ]);
        });

        session()->flash(
            'success',
            'Payroll berhasil dibuat.'
        );

        return redirect()->route(
            'owner.payrolls.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.payrolls.generate',
            [
                'employees' => Employee::query()
                    ->where(
                        'employment_status',
                        'active'
                    )
                    ->orderBy('full_name')
                    ->get(),
            ]
        )->layout('layouts.app');
    }
}