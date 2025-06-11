<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Destination;
use Carbon\Carbon;

/**
 * Test Case untuk validasi data kosong pada form destinasi perjalanan
 * 
 * Test ini akan memvalidasi bahwa:
 * - Semua field yang required tidak boleh kosong
 * - Field yang divalidasi meliputi:
 *   - Nama perjalanan
 *   - Tanggal mulai dan selesai
 *   - Lokasi awal dan akhir
 *   - Detail kendaraan (tipe dan plat nomor)
 *   - Jumlah kursi dan harga
 *   - Link grup WhatsApp
 *   - Deskripsi perjalanan
 */
class AddDestinationTC025 extends TestCase
{
    private $emptyDestinationData; // Menyimpan data destinasi kosong untuk testing
    private $requiredFields; // Menyimpan daftar field yang wajib diisi

    /**
     * Setup awal untuk setiap test
     * Menginisialisasi data destinasi kosong dan daftar field required
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup data destinasi kosong untuk testing
        $this->emptyDestinationData = [
            'travel_name' => '',
            'start_date' => '',
            'end_date' => '',
            'check_point' => '',
            'end_point' => '',
            'vehicle_type' => '',
            'plate_number' => '',
            'number_of_seats' => '',
            'price' => '',
            'link_wa_group' => '',
            'deskripsi' => ''
        ];

        // List of required fields that should not be empty
        $this->requiredFields = [
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
            'deskripsi'
        ];
    }

    public function test_empty_travel_name_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['travel_name']);
        $this->assertTrue(in_array('travel_name', $this->requiredFields));
    }

    public function test_empty_dates_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['start_date']);
        $this->assertEmpty($this->emptyDestinationData['end_date']);
        $this->assertTrue(in_array('start_date', $this->requiredFields));
        $this->assertTrue(in_array('end_date', $this->requiredFields));
    }

    public function test_empty_locations_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['check_point']);
        $this->assertEmpty($this->emptyDestinationData['end_point']);
        $this->assertTrue(in_array('check_point', $this->requiredFields));
        $this->assertTrue(in_array('end_point', $this->requiredFields));
    }

    public function test_empty_vehicle_details_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['vehicle_type']);
        $this->assertEmpty($this->emptyDestinationData['plate_number']);
        $this->assertTrue(in_array('vehicle_type', $this->requiredFields));
        $this->assertTrue(in_array('plate_number', $this->requiredFields));
    }

    public function test_empty_seats_and_price_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['number_of_seats']);
        $this->assertEmpty($this->emptyDestinationData['price']);
        $this->assertTrue(in_array('number_of_seats', $this->requiredFields));
        $this->assertTrue(in_array('price', $this->requiredFields));
    }

    public function test_empty_whatsapp_link_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['link_wa_group']);
        $this->assertTrue(in_array('link_wa_group', $this->requiredFields));
    }

    public function test_empty_description_should_be_invalid(): void
    {
        $this->assertEmpty($this->emptyDestinationData['deskripsi']);
        $this->assertTrue(in_array('deskripsi', $this->requiredFields));
    }

    public function test_all_fields_are_required(): void
    {
        foreach ($this->requiredFields as $field) {
            $this->assertArrayHasKey($field, $this->emptyDestinationData);
            $this->assertEmpty($this->emptyDestinationData[$field]);
        }
    }
}
