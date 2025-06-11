<?php

namespace Tests\Feature;

use App\Models\Driver;
use Database\Seeders\DriverSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddDestinationTC025 extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test untuk fitur menambah destinasi perjalanan baru oleh driver dengan input kosong
     * 
     * Test ini akan:
     * - Login sebagai driver
     * - Mengisi form tambah destinasi dengan data kosong
     * - Memastikan validasi error muncul
     * - Memastikan redirect kembali ke form dengan pesan error
     */
    public function test_add_destination_with_empty_inputs(): void
    {
        // Seed the driver data
        $this->seed(DriverSeeder::class);
        
        // Get the first driver (Alex Brown)
        $driver = Driver::where('email', 'alex@driver.com')->first();
        
        // Set driver session
        session(['driver_id' => $driver->id]);
        session(['is_driver' => true]);

        // Simulate being on Add Destination page
        $response = $this->get(route('driver.add-destination'));
        $response->assertStatus(200);

        // Submit the form with empty data
        $response = $this->post(route('driver.add-destination.post'), [
            'travel_name' => '',
            'start_date' => '',
            'end_date' => '',
            'check_point' => '',
            'end_point' => '',
            'vehicle_type' => '',
            'plate_number' => '',
            'number_of_seats' => '',
            'foto' => '',
            'price' => '',
            'link_wa_group' => '',
            'deskripsi' => '',
        ]);

        // Assert the response
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHasErrors([
            'travel_name',
            'start_date',
            'end_date',
            'check_point',
            'end_point',
            'vehicle_type',
            'plate_number',
            'number_of_seats',
            'foto',
            'price',
            'link_wa_group',
            'deskripsi'
        ]);
    }
}
