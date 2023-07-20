<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_id');
            $table->integer('work_id');
            $table->integer('status')->default(1)->comment('1: Requesting, 2:Decline');
            $table->longText('remarks')->nullable();
            $table->date('request_date')->nullable();
            $table->integer('why_reject')->nullable();
            $table->text('other_note')->nullable();
            $table->enum('display', ['on', 'off'])->default('on');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
