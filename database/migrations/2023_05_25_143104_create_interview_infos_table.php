<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('interview_id')->nullable();
            $table->longText('calendar_interview')->nullable();
            $table->integer('status')->nullable()->comment('interview  decline');
            $table->string('url_zoom')->nullable();
            $table->string('id_zoom')->nullable();
            $table->string('password_zoom')->nullable();
            $table->longText('remark')->nullable();
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
        Schema::dropIfExists( 'interview_infos');
    }
}
