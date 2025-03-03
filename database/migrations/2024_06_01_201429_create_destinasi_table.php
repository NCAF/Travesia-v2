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
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_destinasi', 255);
            $table->string('destinasi_awal', 255);
            $table->string('destinasi_akhir', 255);
            $table->string('jenis_kendaraan', 50);
            $table->string('no_plat', 20);
            $table->datetime('hari_berangkat');
            $table->integer('jumlah_kursi');
            $table->integer('jumlah_bagasi');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_kursi', 10, 2);
            $table->decimal('harga_bagasi', 10, 2);
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
