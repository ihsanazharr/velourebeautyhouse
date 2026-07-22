<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Customer extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'birth_date',
        'notes',
        'is_active',
        'last_visit_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'last_visit_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingCustomers()
    {
        return $this->hasMany(
            BookingCustomer::class
        );
    }

    public function bookings()
    {
        return $this->belongsToMany(
            Booking::class,
            'booking_customers'
        );
    }
}