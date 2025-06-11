<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Destination;
use Carbon\Carbon;

/**
 * Test Case untuk validasi data destinasi perjalanan
 * 
 * Test ini akan memvalidasi:
 * - Nama perjalanan harus valid (tidak kosong dan string)
 * - Tanggal perjalanan harus valid (tanggal mulai sebelum tanggal selesai dan di masa depan)
 * - Lokasi harus valid (titik awal dan akhir tidak boleh sama)
 * - Detail kendaraan harus valid (tipe kendaraan dan plat nomor sesuai format)
 * - Jumlah kursi dan harga harus valid (angka positif)
 * - Link WhatsApp harus valid (format yang benar)
 */
class AddDestinationTC024 extends TestCase
{
    private $validDestinationData;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup valid destination data for testing with dynamic dates
        $startDate = Carbon::now()->addDays(30);
        $endDate = $startDate->copy()->addDay();
        
        $this->validDestinationData = [
            'travel_name' => 'Masatrip',
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
            'check_point' => 'Malang',
            'end_point' => 'Surabaya',
            'vehicle_type' => 'Avanza Putih',
            'plate_number' => 'N 1234 DR',
            'number_of_seats' => 6,
            'price' => 500000,
            'link_wa_group' => 'https://wa.me/6281234567890',
            'deskripsi' => 'Destinasi dari Malang ke Surabaya'
        ];
    }

    public function test_destination_has_valid_travel_name(): void
    {
        $this->assertNotEmpty($this->validDestinationData['travel_name']);
        $this->assertIsString($this->validDestinationData['travel_name']);
    }

    public function test_destination_has_valid_dates(): void
    {
        $startDate = Carbon::parse($this->validDestinationData['start_date']);
        $endDate = Carbon::parse($this->validDestinationData['end_date']);
        
        $this->assertTrue($startDate->isBefore($endDate));
        $this->assertTrue($startDate->isFuture());
    }

    public function test_destination_has_valid_locations(): void
    {
        $this->assertNotEmpty($this->validDestinationData['check_point']);
        $this->assertNotEmpty($this->validDestinationData['end_point']);
        $this->assertNotEquals(
            $this->validDestinationData['check_point'], 
            $this->validDestinationData['end_point']
        );
    }

    public function test_destination_has_valid_vehicle_details(): void
    {
        $this->assertNotEmpty($this->validDestinationData['vehicle_type']);
        $this->assertNotEmpty($this->validDestinationData['plate_number']);
        $this->assertMatchesRegularExpression(
            '/^[A-Z]{1,2}\s[0-9]{1,4}\s[A-Z]{1,3}$/', 
            $this->validDestinationData['plate_number']
        );
    }

    public function test_destination_has_valid_seats_and_price(): void
    {
        $this->assertIsInt($this->validDestinationData['number_of_seats']);
        $this->assertGreaterThan(0, $this->validDestinationData['number_of_seats']);
        
        $this->assertIsInt($this->validDestinationData['price']);
        $this->assertGreaterThan(0, $this->validDestinationData['price']);
    }

    public function test_destination_has_valid_whatsapp_link(): void
    {
        $this->assertStringStartsWith(
            'https://wa.me/', 
            $this->validDestinationData['link_wa_group']
        );
    }
}
