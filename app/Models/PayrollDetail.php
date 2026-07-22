<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $guarded = [];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}