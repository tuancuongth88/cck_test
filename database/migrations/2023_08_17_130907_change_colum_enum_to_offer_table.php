<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumEnumToOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE offers MODIFY COLUMN display ENUM('on', 'off','delete','stop') DEFAULT 'on'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE offers MODIFY COLUMN display ENUM('on', 'off') DEFAULT 'on'");
        });
    }
}
