<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Destinasi;

class DestinasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Surabaya', 'Malang', 'Kediri', 'Blitar', 'Probolinggo',
            'Pasuruan', 'Madiun', 'Mojokerto', 'Batu'
        ];

        $destinations = [];
        $driverId = 1;
        $counter = 1;
        $statuses = ['orderable', 'traveling', 'arrived'];
        $statusIndex = 0;

        // Generate combinations of cities with past dates (5 days ago to yesterday)
        foreach ($cities as $checkPoint) {
            foreach ($cities as $endPoint) {
                if ($checkPoint !== $endPoint) {
                    $startDate = now()->subDays(rand(1, 5)); // Random date between 5 days ago and yesterday
                    $destinations[] = [
                        'driver_id' => $driverId,
                        'kode_destinasi' => 'DST' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                        'travel_name' => $checkPoint . ' - ' . $endPoint . ' Travel (Past)',
                        'start_date' => $startDate,
                        'end_date' => $startDate->copy()->addHours(rand(3, 12)),
                        'check_point' => $checkPoint,
                        'end_point' => $endPoint,
                        'vehicle_type' => $this->getRandomVehicle(),
                        'plate_number' => $this->generateRandomPlateNumber(),
                        'number_of_seats' => rand(4, 50),
                        'price' => rand(100000, 1000000),
                        'link_wa_group' => 'https://wa.me/6281234567890',
                        'foto' => 'destination_' . $counter . '.jpg',
                        'deskripsi' => 'Perjalanan nyaman dari ' . $checkPoint . ' menuju ' . $endPoint . ' dengan pemandangan yang indah.',
                        'status' => $statuses[$statusIndex],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $counter++;
                    $driverId = ($driverId % 3) + 1;
                    $statusIndex = ($statusIndex + 1) % 3;
                }
            }
        }

        // Generate combinations of cities with future dates (today to next 30 days)
        foreach ($cities as $checkPoint) {
            foreach ($cities as $endPoint) {
                if ($checkPoint !== $endPoint) {
                    $startDate = now()->addDays(rand(0, 30)); // Random date between today and next 30 days
                    $destinations[] = [
                        'driver_id' => $driverId,
                        'kode_destinasi' => 'DST' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                        'travel_name' => $checkPoint . ' - ' . $endPoint . ' Travel (Future)',
                        'start_date' => $startDate,
                        'end_date' => $startDate->copy()->addHours(rand(3, 12)),
                        'check_point' => $checkPoint,
                        'end_point' => $endPoint,
                        'vehicle_type' => $this->getRandomVehicle(),
                        'plate_number' => $this->generateRandomPlateNumber(),
                        'number_of_seats' => rand(4, 50),
                        'price' => rand(100000, 1000000),
                        'link_wa_group' => 'https://wa.me/6281234567890',
                        'foto' => 'destination_' . $counter . '.jpg',
                        'deskripsi' => 'Perjalanan nyaman dari ' . $checkPoint . ' menuju ' . $endPoint . ' dengan pemandangan yang indah.',
                        'status' => $statuses[$statusIndex],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $counter++;
                    $driverId = ($driverId % 3) + 1;
                    $statusIndex = ($statusIndex + 1) % 3;
                }
            }
        }

        // Insert in chunks for better performance
        foreach (array_chunk($destinations, 50) as $chunk) {
            DB::table('destinasi')->insert($chunk);
        }
    }

    private function getRandomVehicle()
    {
        $vehicles = ['Bus', 'Mini Bus', 'Van', 'SUV', 'MPV', 'Sedan'];
        return $vehicles[array_rand($vehicles)];
    }

    private function generateRandomPlateNumber()
    {
        $areas = ['L', 'W', 'N', 'B', 'D', 'S'];
        $area = $areas[array_rand($areas)];
        $numbers = rand(1000, 9999);
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        
        return $area . ' ' . $numbers . ' ' . $letters;
    }
}
