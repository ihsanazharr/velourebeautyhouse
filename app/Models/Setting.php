<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'working_days' => 'array',
            'booking_enabled' => 'boolean',
        ];
    }
}