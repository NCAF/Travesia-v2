<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnAndAddTriggerInDestinasiTable extends Migration
{
    public function up()
    {
        Schema::table('destinasi', function (Blueprint $table) {
            $table->enum('status', ['orderable', 'traveling', 'arrived'])->default('orderable')->change();
        });
    }

    public function down()
    {
        Schema::table('destinasi', function (Blueprint $table) {
            $table->string('status')->default('orderable')->change();
        });
    }
}
