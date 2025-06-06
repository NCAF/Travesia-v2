<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_destinasi', 255);
            $table->string('travel_name', 255);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('check_point', 255);
            $table->string('end_point', 255);
            $table->string('vehicle_type', 50);
            $table->string('plate_number', 20);
            $table->integer('number_of_seats');
            $table->integer('price');
            $table->string('link_wa_group');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinasi');
    }
};
