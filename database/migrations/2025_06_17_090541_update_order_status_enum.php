<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include 'cancelled' and 'pending' states
        DB::statement("ALTER TABLE orders MODIFY status ENUM('order', 'paid', 'finished', 'cancelled', 'pending', 'pending_payment', 'canceled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE orders MODIFY status ENUM('order', 'paid', 'finished', 'canceled') DEFAULT 'order'");
    }
};
