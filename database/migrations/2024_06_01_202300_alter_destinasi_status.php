<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Step 1: Alter the column type and set the default without an inline CHECK.
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status SET DEFAULT 'orderable'");
        
        // Step 2: Make the column NOT NULL and drop identity (if it exists).
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status SET NOT NULL");
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status DROP IDENTITY IF EXISTS");

        // Step 3: Drop an existing check constraint, if any.
        DB::statement("ALTER TABLE destinasi DROP CONSTRAINT IF EXISTS destinasi_status_check");

        // Step 4: Add the check constraint as a separate statement.
        DB::statement("ALTER TABLE destinasi ADD CONSTRAINT destinasi_status_check CHECK (status IN ('orderable', 'traveling', 'arrived'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        // Remove the check constraint.
        DB::statement("ALTER TABLE destinasi DROP CONSTRAINT IF EXISTS destinasi_status_check");
        
        // Revert the column to its previous state.
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status DROP DEFAULT");
        DB::statement("ALTER TABLE destinasi ALTER COLUMN status DROP NOT NULL");
    }
}; 