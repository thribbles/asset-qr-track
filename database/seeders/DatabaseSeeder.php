<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@assect.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create officer user
        User::create([
            'name' => 'Officer',
            'email' => 'officer@assect.local',
            'password' => Hash::make('password'),
            'role' => 'officer',
        ]);

        // Create sample locations
        $locations = [
            ['building' => 'Main Building', 'floor' => '1', 'room' => '101', 'detail' => 'Office'],
            ['building' => 'Main Building', 'floor' => '1', 'room' => '102', 'detail' => 'Meeting Room'],
            ['building' => 'Main Building', 'floor' => '2', 'room' => '201', 'detail' => 'IT Department'],
            ['building' => 'Annex', 'floor' => '1', 'room' => 'A01', 'detail' => 'Storage'],
            ['building' => 'Warehouse', 'floor' => 'G', 'room' => 'W01', 'detail' => 'Main Storage'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
