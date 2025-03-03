<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_destinasi_insert
            AFTER INSERT ON destinasi
            FOR EACH ROW
            BEGIN
                DECLARE nama_user VARCHAR(100);
                DECLARE channel_name VARCHAR(255);
                DECLARE welcome_message TEXT;

                -- Mendapatkan nama user pembuat destinasi
                SELECT nama INTO nama_user FROM users WHERE id = NEW.user_id;

                -- Membuat nama channel
                SET channel_name = CONCAT("Destinasi ", NEW.kode_destinasi);

                -- Membuat pesan sambutan
                SET welcome_message = "Selamat datang di perjalanan ini! Kami senang Anda memilih untuk bergabung dengan kami. Jika ada pertanyaan atau kebutuhan selama perjalanan, jangan ragu untuk menghubungi kami di sini. Selamat menikmati perjalanan Anda!";

                -- Memasukkan ke dalam tabel chats
                INSERT INTO chats (destinasi_id, nama_channel, created_at, updated_at)
                VALUES (NEW.id, channel_name, NOW(), NOW());

                -- Memasukkan pesan sambutan ke dalam tabel messages
                INSERT INTO messages (chat_id, sender_id, message, created_at, updated_at)
                VALUES (LAST_INSERT_ID(), NEW.user_id, welcome_message, NOW(), NOW());
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_destinasi_insert');
    }
};
