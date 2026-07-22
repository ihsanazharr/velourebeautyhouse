<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'booking_date',
        'booking_start',
        'booking_end',
        'dp_amount',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'booking_start' => 'datetime',
            'booking_end' => 'datetime',
            'dp_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookingCustomers()
    {
        return $this->hasMany(BookingCustomer::class);
    }

    public function services()
    {
        return $this->hasMany(BookingService::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}