<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'user_id' => 1, // Assuming user ID 1 exists
            'destinasi_id' => 2, // Assuming destinasi ID 2 exists
            'jumlah_kursi' => 2,
            'harga_kursi' => 450000.00,
            'status' => 'order',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('orders')->insert([
            'user_id' => 2, // Assuming user ID 2 exists
            'destinasi_id' => 1, // Assuming destinasi ID 1 exists
            'jumlah_kursi' => 3,
            'harga_kursi' => 350000.00,
            'status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('orders')->insert([
            'user_id' => 3, // Assuming user ID 3 exists
            'destinasi_id' => 3, // Assuming destinasi ID 3 exists
            'jumlah_kursi' => 1,
            'harga_kursi' => 550000.00,
            'status' => 'finished',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('orders')->insert([
            'user_id' => 1, // Assuming user ID 1 exists
            'destinasi_id' => 3, // Assuming destinasi ID 3 exists
            'jumlah_kursi' => 4,
            'harga_kursi' => 550000.00,
            'status' => 'canceled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 