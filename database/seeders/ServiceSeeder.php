<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $hair = ServiceCategory::where('slug', 'hair')->first();
        $eyelash = ServiceCategory::where('slug', 'eye-lash')->first();
        $nails = ServiceCategory::where('slug', 'nails')->first();

        $services = [

            // HAIR

            [
                'service_category_id' => $hair->id,
                'name' => 'Haircut',
                'description' => 'Haircut Service',
                'duration_minutes' => 60,
                'price' => 50000,
            ],

            [
                'service_category_id' => $hair->id,
                'name' => 'Cuci Blow',
                'description' => 'Cuci Blow Service',
                'duration_minutes' => 45,
                'price' => 50000,
            ],

            [
                'service_category_id' => $hair->id,
                'name' => 'Cuci Catok',
                'description' => 'Cuci Catok Service',
                'duration_minutes' => 45,
                'price' => 60000,
            ],

            [
                'service_category_id' => $hair->id,
                'name' => 'Creambath',
                'description' => 'Creambath Service',
                'duration_minutes' => 60,
                'price' => 80000,
            ],

            [
                'service_category_id' => $hair->id,
                'name' => 'Hair Mist',
                'description' => 'Hair Mist Service',
                'duration_minutes' => 60,
                'price' => 75000,
            ],

            [
                'service_category_id' => $hair->id,
                'name' => 'Coloring',
                'description' => 'Hair Coloring Service',
                'duration_minutes' => 180,
                'price' => 300000,
            ],

            // EYE LASH

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Lash Lift',
                'description' => 'Lash Lift Service',
                'duration_minutes' => 60,
                'price' => 150000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Eye Lash Natural',
                'description' => 'Natural Eye Lash',
                'duration_minutes' => 120,
                'price' => 200000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Eye Lash Double',
                'description' => 'Double Eye Lash',
                'duration_minutes' => 120,
                'price' => 250000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Eye Lash Volume',
                'description' => 'Volume Eye Lash',
                'duration_minutes' => 120,
                'price' => 300000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Whispy',
                'description' => 'Whispy Eye Lash',
                'duration_minutes' => 120,
                'price' => 300000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Retouch',
                'description' => 'Retouch Service',
                'duration_minutes' => 60,
                'price' => 100000,
            ],

            [
                'service_category_id' => $eyelash->id,
                'name' => 'Remove',
                'description' => 'Remove Service',
                'duration_minutes' => 60,
                'price' => 50000,
            ],

            // NAILS

            [
                'service_category_id' => $nails->id,
                'name' => 'Manicure Only',
                'description' => 'Perawatan manicure standar tanpa gel polish',
                'duration_minutes' => 30,
                'price' => 45000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Manicure + Gel Polish',
                'description' => 'Manicure lengkap dengan cat kuku gel premium',
                'duration_minutes' => 90,
                'price' => 80000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Manicure + Softtip + Gel Polish',
                'description' => 'Manicure dengan ekstensi softtip dan cat kuku gel',
                'duration_minutes' => 120,
                'price' => 170000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Manicure + Overlay + Gel Polish',
                'description' => 'Manicure dengan lapisan overlay pelindung kuku dan cat gel',
                'duration_minutes' => 120,
                'price' => 160000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Manicure + Polygel + Gel Polish',
                'description' => 'Manicure dengan ekstensi polygel kuat dan cat kuku gel',
                'duration_minutes' => 180,
                'price' => 240000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Pedicure Express',
                'description' => 'Perawatan pedicure cepat pembersihan kuku kaki',
                'duration_minutes' => 60,
                'price' => 65000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Pedicure Spa',
                'description' => 'Pedicure spa mewah dengan relaksasi dan pijat kaki',
                'duration_minutes' => 120,
                'price' => 140000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Pedicure Express + Gel Polish',
                'description' => 'Pedicure cepat dengan aplikasi cat kuku gel',
                'duration_minutes' => 120,
                'price' => 100000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Pedicure Spa + Gel Polish',
                'description' => 'Pedicure spa lengkap dengan cat kuku gel premium',
                'duration_minutes' => 150,
                'price' => 175000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Remove with treatment (10 Jari)',
                'description' => 'Pelepasan gel/ekstensi dengan perawatan kuku (Rp 5.000 / jari)',
                'duration_minutes' => 40,
                'price' => 50000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Remove only (10 Jari)',
                'description' => 'Pelepasan gel/ekstensi saja tanpa treatment tambahan (Rp 7.000 / jari)',
                'duration_minutes' => 40,
                'price' => 70000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Overlay (per Jari)',
                'description' => 'Tambahan lapisan pelindung overlay per kuku',
                'duration_minutes' => 5,
                'price' => 8000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Softtip (per Jari)',
                'description' => 'Tambahan ekstensi softtip per kuku',
                'duration_minutes' => 10,
                'price' => 9000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Polygel (per Jari)',
                'description' => 'Tambahan polygel per kuku',
                'duration_minutes' => 15,
                'price' => 16000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Nail Art (per Jari)',
                'description' => 'Desain nail art khusus per kuku (Mulai dari Rp 3.000 - Rp 20.000)',
                'duration_minutes' => 10,
                'price' => 3000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Accessories (per Kuku)',
                'description' => 'Pemasangan aksesoris per kuku (Mulai dari Rp 2.000 - Rp 20.000)',
                'duration_minutes' => 5,
                'price' => 2000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional 3D Nail Art (per Jari)',
                'description' => 'Nail art 3 dimensi per kuku (Mulai dari Rp 5.000 - Rp 15.000)',
                'duration_minutes' => 15,
                'price' => 5000,
            ],
            [
                'service_category_id' => $nails->id,
                'name' => 'Additional Special Polish Effect',
                'description' => 'Efek khusus (Cat Eye, Chrome, Blush, Gold & Silver Flakes)',
                'duration_minutes' => 15,
                'price' => 5000,
            ],
        ];

        foreach ($services as $service) {

            Service::firstOrCreate(
                [
                    'service_category_id' => $service['service_category_id'],
                    'name' => $service['name'],
                ],
                [
                    'description' => $service['description'],
                    'duration_minutes' => $service['duration_minutes'],
                    'price' => $service['price'],

                    'commission_rate' => 0,

                    'requires_booking' => true,

                    'is_active' => true,
                ]
            );
        }
    }
}