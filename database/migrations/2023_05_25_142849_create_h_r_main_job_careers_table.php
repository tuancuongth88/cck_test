<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHRMainJobCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrs_main_job_career', function (Blueprint $table) {
            $table->id();
            $table->integer('hrs_id');
            $table->date('main_job_career_date_from')->nullable();
            $table->date('main_job_career_date_to')->nullable();
            $table->integer('department_id')->nullable()->comment('reference job info table');
            $table->integer('job_id')->nullable()->comment('reference job info table');
            $table->longText('detail')->nullable();
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
        Schema::dropIfExists('hrs_main_job_career');
    }
}
