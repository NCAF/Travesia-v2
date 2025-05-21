<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('driver')->insert([
            'role' => 'driver',
            'name' => 'Alex Brown',
            'email' => 'alex@driver.com',
            'password' => Hash::make('driver123'),
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('driver')->insert([
            'role' => 'driver',
            'name' => 'Sarah Wilson',
            'email' => 'sarah@driver.com',
            'password' => Hash::make('driver123'),
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('driver')->insert([
            'role' => 'driver',
            'name' => 'Michael Clark',
            'email' => 'michael@driver.com',
            'password' => Hash::make('driver123'),
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 