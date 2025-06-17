<?php

// Mendefinisikan namespace untuk test unit
namespace Tests\Unit;

// Import class-class yang dibutuhkan
use Tests\TestCase;
use App\Models\Driver;
use App\Models\Destinasi;
use Database\Seeders\DriverSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

// Class test untuk fitur Add Destination
class AddDestination extends TestCase
{
    // Menggunakan trait RefreshDatabase untuk reset database setiap test
    // dan WithFaker untuk generate data palsu
    use RefreshDatabase, WithFaker;

    // Property untuk menyimpan instance driver
    protected $driver;

    // Method yang dijalankan sebelum setiap test
    protected function setUp(): void
    {
        // Memanggil setUp parent class
        parent::setUp();
        
        // Membuat direktori images jika belum ada
        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0777, true);
        }
        
        // Menjalankan seeder untuk membuat data driver
        $this->seed(DriverSeeder::class);
        
        // Mengambil driver pertama dari database
        $this->driver = Driver::first();
        
        // Mensimulasikan session driver yang login
        session(['driver_id' => $this->driver->id]);
        session(['is_driver' => true]);
    }

    /**
     * Test untuk memastikan destinasi berhasil dibuat dengan input valid
     */
    public function test_add_destination_with_valid_inputs(): void
    {
        // Membuat file gambar palsu
        $file = UploadedFile::fake()->create('destination.jpg', 100, 'image/jpeg');

        // Menyiapkan data input yang valid
        $data = [
            'travel_name' => 'Test Travel',
            'start_date' => now()->addDays(1)->format('Y-m-d H:i'),
            'end_date' => now()->addDays(2)->format('Y-m-d H:i'),
            'check_point' => 1111111,
            'end_point' => 'Bandung',
            'vehicle_type' => 'Bus',
            'plate_number' => 'B 1234 CD',
            'number_of_seats' => 40,
            'price' => 150000,
            'link_wa_group' => 'https://wa.me/group/xyz',
            'deskripsi' => 'Test travel description',
            'foto' => $file
        ];

        // Mengirim request POST untuk membuat destinasi
        $response = $this->post(route('driver.add-destination.post'), $data);

        // Memastikan response redirect ke halaman destination list
        $response->assertRedirect(route('driver.destination-list'));
        $response->assertSessionHas('success', 'Berhasil menambahkan destinasi.');

        // Mengambil destinasi yang baru dibuat
        $destinasi = Destinasi::where('travel_name', 'Test Travel')->first();
        $this->assertNotNull($destinasi, 'Destination was not created');

        // Memastikan data yang tersimpan sesuai dengan input
        $this->assertEquals('Test Travel', $destinasi->travel_name);
        $this->assertEquals('Jakarta', $destinasi->check_point);
        $this->assertEquals('Bandung', $destinasi->end_point);
        $this->assertEquals('Bus', $destinasi->vehicle_type);
        $this->assertEquals('B 1234 CD', $destinasi->plate_number);
        $this->assertEquals(40, $destinasi->number_of_seats);
        $this->assertEquals(150000, $destinasi->price);
        $this->assertEquals('https://wa.me/group/xyz', $destinasi->link_wa_group);
        $this->assertEquals('Test travel description', $destinasi->deskripsi);
        $this->assertEquals($this->driver->id, $destinasi->driver_id);

        // Memastikan file gambar tersimpan
        $this->assertNotNull($destinasi->foto);
        $this->assertFileExists(public_path('images/' . $destinasi->foto));
    }

    /**
     * Test untuk memastikan validasi error saat input kosong
     */
    public function test_add_destination_with_empty_inputs(): void
    {
        // Mengirim request POST dengan data kosong
        $response = $this->post(route('driver.add-destination.post'), []);

        // Memastikan response redirect dengan error
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'travel_name',
            'start_date', 
            'end_date',
            'check_point',
            'end_point',
            'vehicle_type',
            'plate_number',
            'number_of_seats',
            'price',
            'link_wa_group',
            'foto',
            'deskripsi'
        ]);

        // Memastikan tidak ada destinasi yang dibuat
        $this->assertDatabaseCount('destinasi', 0);
    }
}
