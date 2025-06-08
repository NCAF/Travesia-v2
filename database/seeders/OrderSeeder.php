<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order with status 'order' (waiting for payment)
        DB::table('orders')->insert([
            'user_id' => 1,
            'destinasi_id' => 2,
            'jumlah_kursi' => 2,
            'harga_kursi' => 450000.00,
            'status' => 'order',
            'order_id' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Order with status 'paid'
        DB::table('orders')->insert([
            'user_id' => 2,
            'destinasi_id' => 1,
            'jumlah_kursi' => 3,
            'harga_kursi' => 350000.00,
            'status' => 'paid',
            'order_id' => Str::uuid(),
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(1),
        ]);

        // Order with status 'finished'
        DB::table('orders')->insert([
            'user_id' => 3,
            'destinasi_id' => 3,
            'jumlah_kursi' => 1,
            'harga_kursi' => 550000.00,
            'status' => 'finished',
            'order_id' => Str::uuid(),
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subHours(12),
        ]);

        // Order with status 'canceled'
        DB::table('orders')->insert([
            'user_id' => 1,
            'destinasi_id' => 3,
            'jumlah_kursi' => 4,
            'harga_kursi' => 550000.00,
            'status' => 'canceled',
            'order_id' => Str::uuid(),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Additional orders for more variety
        DB::table('orders')->insert([
            'user_id' => 2,
            'destinasi_id' => 2,
            'jumlah_kursi' => 2,
            'harga_kursi' => 450000.00,
            'status' => 'finished',
            'order_id' => Str::uuid(),
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(2),
        ]);

        DB::table('orders')->insert([
            'user_id' => 3,
            'destinasi_id' => 1,
            'jumlah_kursi' => 1,
            'harga_kursi' => 350000.00,
            'status' => 'paid',
            'order_id' => Str::uuid(),
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(3),
        ]);

        DB::table('orders')->insert([
            'user_id' => 1,
            'destinasi_id' => 1,
            'jumlah_kursi' => 3,
            'harga_kursi' => 350000.00,
            'status' => 'order',
            'order_id' => Str::uuid(),
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);

        DB::table('orders')->insert([
            'user_id' => 2,
            'destinasi_id' => 3,
            'jumlah_kursi' => 2,
            'harga_kursi' => 550000.00,
            'status' => 'canceled',
            'order_id' => Str::uuid(),
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);
    }
} 