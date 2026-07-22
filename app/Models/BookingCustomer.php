<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingCustomer extends Model
{
    protected $guarded = [];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}