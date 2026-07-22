<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            SettingSeeder::class,
            OwnerSeeder::class,
        ]);

        $customerUser = User::firstOrCreate(
            [
                'email' => 'customer@email.com',
            ],
            [
                'name' => 'Jane Customer',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $customerUser->assignRole('customer');

        \App\Models\Customer::firstOrCreate(
            [
                'user_id' => $customerUser->id,
            ],
            [
                'name' => 'Jane Customer',
                'phone' => '081234567890',
                'email' => 'customer@email.com',
                'is_active' => true,
            ]
        );
    }
}
