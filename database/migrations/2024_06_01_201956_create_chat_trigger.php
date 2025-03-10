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
        // Create the trigger function using PL/pgSQL
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_after_destinasi_insert() 
            RETURNS trigger AS $$
            DECLARE
                nama_user varchar(100);
                channel_name varchar(255);
                welcome_message text;
                chat_id int;
            BEGIN
                -- Mendapatkan nama user pembuat destinasi
                SELECT nama INTO nama_user FROM users WHERE id = NEW.user_id;
                
                -- Membuat nama channel
                channel_name := 'Destinasi ' || NEW.kode_destinasi;
                
                -- Membuat pesan sambutan
                welcome_message := 'Selamat datang di perjalanan ini! Kami senang Anda memilih untuk bergabung dengan kami. Jika ada pertanyaan atau kebutuhan selama perjalanan, jangan ragu untuk menghubungi kami di sini. Selamat menikmati perjalanan Anda!';
                
                -- Memasukkan ke dalam tabel chats dan mengambil id chat yang baru
                INSERT INTO chats (destinasi_id, nama_channel, created_at, updated_at)
                VALUES (NEW.id, channel_name, NOW(), NOW())
                RETURNING id INTO chat_id;
                
                -- Memasukkan pesan sambutan ke dalam tabel messages
                INSERT INTO messages (chat_id, sender_id, message, created_at, updated_at)
                VALUES (chat_id, NEW.user_id, welcome_message, NOW(), NOW());
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
            
            CREATE TRIGGER after_destinasi_insert
            AFTER INSERT ON destinasi
            FOR EACH ROW
            EXECUTE FUNCTION fn_after_destinasi_insert();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_destinasi_insert ON destinasi;
            DROP FUNCTION IF EXISTS fn_after_destinasi_insert();
        ");
    }
};
