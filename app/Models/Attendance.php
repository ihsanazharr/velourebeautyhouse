<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'checkin_time' => 'datetime',
            'checkout_time' => 'datetime',
            'late_penalty' => 'decimal:2',
            'early_checkout_penalty' => 'decimal:2',
            'meal_allowance' => 'decimal:2',
            'overtime_amount' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}