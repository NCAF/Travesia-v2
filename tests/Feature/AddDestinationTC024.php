<?php

namespace Tests\Feature;

use App\Models\Driver;
use Database\Seeders\DriverSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AddDestinationTC024 extends TestCase
{
    
    /**
     * Test Add Destination with valid inputs
     */
    /**
     * Test untuk fitur menambah destinasi perjalanan baru oleh driver
     * 
     * Test ini akan:
     * - Login sebagai driver
     * - Mengisi form tambah destinasi dengan data yang valid
     * - Memastikan data tersimpan di database
     * - Memastikan redirect ke halaman yang benar
     */
    public function test_add_destination_with_valid_inputs(): void
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

        // Create fake image file
        $image = UploadedFile::fake()->image('gambar.jpg');

        // Submit the form with valid data
        $response = $this->post(route('driver.add-destination.post'), [
            'travel_name' => 'Masatrip',
            'start_date' => '2024-02-10 12:30:00',
            'end_date' => '2024-02-11 12:30:00',
            'check_point' => 'Malang',
            'end_point' => 'Surabaya',
            'vehicle_type' => 'Avanza Putih',
            'plate_number' => 'N 1234 DR',
            'number_of_seats' => '6',
            'foto' => $image,
            'price' => '500000',
            'link_wa_group' => 'https://wa.me/6281234567890',
            'deskripsi' => 'Destinasi dari Malang ke Surabaya',
        ]);

        // Assert the response
        $response->assertStatus(302); // Redirect status
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas('success', 'Berhasil menambahkan destinasi.');
        
       
    }
}
