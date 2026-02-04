<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingLocation;

class ParkingLocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'Bukoto Brown Flats Parking',
                'address' => '123 Main Street, Downtown',
                'lat' => 0.3476,
                'lng' => 32.5825,
                'rating' => 4.5,
                'price' => 3000,
                'available' => 45,
                'total' => 100,
                'distance' => 0.8,
                'image' => 'park/bd.jpg',
                'features' => ['24/7 Security', 'CCTV', 'Covered', 'EV Charging'],
                'is_active' => true,
            ],
            [
                'name' => 'Bwaise Parking',
                'address' => 'Bwaise Parking',
                'lat' => 0.3136,
                'lng' => 32.5811,
                'rating' => 4.8,
                'price' => 5000,
                'available' => 12,
                'total' => 150,
                'distance' => 2.1,
                // 'image' => 'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=800',
                'image' => 'park/bd.jpg',

                'features' => ['Mall Access', 'Covered Parking', 'Security Guards', 'Wheelchair Accessible'],
                'is_active' => true,
            ],
            [
                'name' => 'Nakasero Market Parking',
                'address' => 'Nakasero Road, Kampala',
                'lat' => 0.3280,
                'lng' => 32.5792,
                'rating' => 4.2,
                'price' => 2000,
                'available' => 28,
                'total' => 60,
                'distance' => 1.5,
                'image' => 'park/Kampala-ciuty.jpg',
                'features' => ['Open Air', 'CCTV', 'Near Market', 'Motorcycle Parking'],
                'is_active' => true,
            ],
            [
                'name' => 'Acacia Mall Parking',
                'address' => 'Kisementi, Kololo',
                'lat' => 0.3367,
                'lng' => 32.5958,
                'rating' => 4.7,
                'price' => 6000,
                'available' => 67,
                'total' => 200,
                'distance' => 3.2,
                'image' => 'park/ka1.jpg',
                'features' => ['Multi-level', 'Valet Service', 'EV Charging', 'Premium Security'],
                'is_active' => true,
            ],
            [
                'name' => 'Client Parking',
                'address' => 'Client Parking',
                'lat' => 0.3163,
                'lng' => 32.5811,
                'rating' => 3.9,
                'price' => 2500,
                'available' => 8,
                'total' => 80,
                'distance' => 1.2,
                'image' => 'park/own.webp',
                'features' => ['City Center', '24/7 Access', 'CCTV', 'Pay & Display'],
                'is_active' => true,
            ],
            [
                'name' => 'Sheraton Hotel Parking',
                'address' => 'Ternan Avenue, Kampala',
                'lat' => 0.3239,
                'lng' => 32.5758,
                'rating' => 4.6,
                'price' => 8000,
                'available' => 34,
                'total' => 120,
                'distance' => 1.8,
                'image' => 'park/Jinja.jpg',
                'features' => ['Hotel Guest Priority', 'Valet Available', 'Covered', 'Premium Security'],
                'is_active' => true,
            ],
            [
                'name' => 'Jinja Town Parking',
                'address' => 'Jinja Town Center',
                'lat' => 0.4254,
                'lng' => 33.2045,
                'rating' => 4.3,
                'price' => 1500,
                'available' => 25,
                'total' => 50,
                'distance' => 5.5,
                'image' => 'park/Jinja-Town.jpg',
                'features' => ['Town Center', 'Security', 'Covered', '24/7 Access'],
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            ParkingLocation::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }
}
