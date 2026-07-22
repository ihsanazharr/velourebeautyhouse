<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'requires_booking' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'service_category_id'
        );
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }
}