<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Driver;
use Database\Seeders\DriverSeeder;

class signupdriver extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sign_up_driver_is_successful()
    {
        $data = [
            'name' => 'Adel Driver',
            'email' => 'adel@gmail.com',
            'password' => 'Adel12345',
            'password_confirmation' => 'Adel12345',
            'image' => UploadedFile::fake()->image('image.jpg')
        ];

        $response = $this->post('/driver/register-driver', $data);

        $response->assertStatus(302); // Redirect berarti sukses
        $this->assertDatabaseHas('driver', [
            'email' => 'adel@gmail.com',
            'name' => 'Adel Driver',
        ]);
    }

    /** @test */
    public function sign_up_fails_if_email_is_duplicate()
    {
        $this->seed(DriverSeeder::class);

        $data = [
            'name' => 'Another Driver',
            'email' => 'driver@gmail.com',
            'password' => 'Driver1234',
            'password_confirmation' => 'Driver1234',
            'image' => UploadedFile::fake()->image('image.jpg')
        ];

        $response = $this->post('/driver/register-driver', $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('driver', 1);
    }
}
