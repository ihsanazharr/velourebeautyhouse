<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            [
                'company_name' => 'VELOURE BEAUTY HOUSE',
            ],
            [
                'address' => 'Jl. Sukamekar III No.2, Sukawarna, Kec. Sukajadi, Kota Bandung, Jawa Barat 40164',

                'phone' => '0882-1889-8999',

                'whatsapp_number' => '0882-1889-8999',

                'email' => null,

                'qris_image' => null,

                'open_time' => '11:00:00',

                'close_time' => '20:00:00',

                'working_days' => json_encode([
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday',
                ]),

                'late_tolerance_minutes' => 10,

                'late_penalty_amount' => 10000,

                'overtime_rate' => 15000,

                'meal_allowance_amount' => 15000,

                'booking_enabled' => true,
            ]
        );
    }
}