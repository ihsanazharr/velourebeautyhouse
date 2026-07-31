<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Employee extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role',
        'employee_code',
        'full_name',
        'phone',
        'birth_date',
        'address',
        'hire_date',
        'basic_salary',
        'commission_percentage',
        'meal_allowance',
        'overtime_rate',
        'employment_status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'basic_salary' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'meal_allowance' => 'decimal:2',
            'overtime_rate' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function adjustments()
    {
        return $this->hasMany(EmployeeAdjustment::class);
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }

    public function getRoleNameAttribute(): ?string
    {
        return $this->role ?? $this->user?->roles->first()?->name;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role_name) {
            'owner' => 'Owner',
            'kasir' => 'Kasir',
            'hairstylist' => 'Hairstylist',
            'nailist_senior' => 'Nailist Senior',
            'nailist_junior' => 'Nailist Junior',
            default => '-',
        };
    }
}