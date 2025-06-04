<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('destinasi')->insert([
            'user_id' => 1, // Assuming user ID 1 exists
            'kode_destinasi' => 'DST001',
            'travel_name' => 'Jakarta City Tour',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2)->addHours(6),
            'check_point' => 'Jakarta Central Station',
            'end_point' => 'Jakarta Old Town',
            'vehicle_type' => 'Mini Bus',
            'plate_number' => 'B 1234 ABC',
            'number_of_seats' => 12,
            'price' => 350000,
            'foto' => 'jakarta_tour.jpg',
            'deskripsi' => 'Enjoy a day tour around Jakarta historical sites and modern landmarks.',
            'status' => 'orderable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('destinasi')->insert([
            'user_id' => 2,
            'kode_destinasi' => 'DST002',
            'travel_name' => 'Bandung Highland Tour',
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(3)->addHours(8),
            'check_point' => 'Bandung Train Station',
            'end_point' => 'Lembang Park',
            'vehicle_type' => 'SUV',
            'plate_number' => 'D 5678 XYZ',
            'number_of_seats' => 6,
            'price' => 450000,
            'foto' => 'bandung_tour.jpg',
            'deskripsi' => 'Experience the beauty of Bandung highlands with cool weather and stunning views.',
            'status' => 'traveling',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('destinasi')->insert([
            'user_id' => 3,
            'kode_destinasi' => 'DST003',
            'travel_name' => 'Yogyakarta Cultural Journey',
            'start_date' => now()->addDays(4),
            'end_date' => now()->addDays(4)->addHours(9),
            'check_point' => 'Yogyakarta Airport',
            'end_point' => 'Malioboro Street',
            'vehicle_type' => 'Van',
            'plate_number' => 'AB 9012 DEF',
            'number_of_seats' => 8,
            'price' => 550000,
            'foto' => 'yogya_tour.jpg',
            'deskripsi' => 'Discover the rich culture and history of Yogyakarta through its temples and palace.',
            'status' => 'arrived',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
