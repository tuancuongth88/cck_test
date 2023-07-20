<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->integer('interview_id')->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('work_id')->nullable();
            $table->integer('status_selection')->default(1)->comment('1: Official offer, 2: Not pass, 3: Official offer confirm, 4: Official offer decline');
            $table->integer('status_period')->nullable()->comment('1: Expiration, 2: yyyymmdd');
            $table->longText('remark')->nullable();
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
        Schema::dropIfExists('results');
    }
}
