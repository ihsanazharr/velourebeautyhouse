<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        ServiceCategory::firstOrCreate(
            ['slug' => 'hair'],
            [
                'name' => 'Hair',
                'is_active' => true,
            ]
        );

        ServiceCategory::firstOrCreate(
            ['slug' => 'eye-lash'],
            [
                'name' => 'Eye Lash',
                'is_active' => true,
            ]
        );

        ServiceCategory::firstOrCreate(
            ['slug' => 'nails'],
            [
                'name' => 'Nails',
                'is_active' => true,
            ]
        );
    }
}