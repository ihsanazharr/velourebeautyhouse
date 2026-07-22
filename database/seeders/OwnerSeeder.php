<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Owner
        $owner = User::firstOrCreate(
            [
                'email' => 'owner@veloure.local',
            ],
            [
                'name' => 'Owner Veloure',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole('owner');

        // Seed Kasir
        $kasir = User::firstOrCreate(
            [
                'email' => 'kasir@veloure.local',
            ],
            [
                'name' => 'Kasir Veloure',
                'password' => Hash::make('password'),
            ]
        );
        $kasir->assignRole('kasir');
    }
}