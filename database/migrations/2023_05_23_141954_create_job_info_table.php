<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_type_id')->unsigned();
            $table->string('name_ja');
            $table->string('name_en');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('job_type_id')->references('id')->on('job_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_info');
    }
}
