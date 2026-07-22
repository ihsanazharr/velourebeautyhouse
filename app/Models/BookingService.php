<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(
            Booking::class
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            Employee::class
        );
    }

    public function service()
    {
        return $this->belongsTo(
            Service::class
        );
    }
}