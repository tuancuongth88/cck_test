<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255)->nullable()->comment('created by user');
            $table->integer('company_id');
            $table->string('title', 255)->nullable();
            $table->date('application_period_from')->nullable();
            $table->date('application_period_to')->nullable();
            $table->longText('job_description')->nullable();
            $table->longText('application_condition')->nullable();
            $table->longText('application_requirement')->nullable();
            $table->longText('other_skill')->nullable();
            $table->longText('preferred_skill')->nullable();
            $table->integer('form_of_employment')->default(1)->comment('1: full time, 2: Contract employee, 3: Temporary employee, 4:  others');
            $table->string('working_time_from', 255)->nullable();
            $table->string('working_time_to', 255)->nullable();
            $table->longText('vacation')->nullable();
            $table->text('expected_income')->nullable();
            $table->string('assumed_annual_income', 255)->nullable();
            $table->integer('city_id')->nullable();
            $table->longText('working_palace_detail')->nullable();
            $table->longText('treatment_welfare')->nullable();
            $table->longText('company_pr_appeal')->nullable();
            $table->integer('bonus_pay_rise')->nullable()->comment('1: yes, 2: no');
            $table->integer('over_time')->nullable()->comment('1: yes, 2: no');
            $table->integer('transfer')->nullable()->comment('1: yes, 2: no');
            $table->integer('passive_smoking')->nullable()->comment('1: yes, 2: no');
            $table->integer('interview_follow')->nullable()->comment('1: First interview, 2: Second interview, 3: Third interview, 4: Fourth interview, 5: Fifth interview');
            $table->integer('status')->default(1)->comment('1: Recruiting, 2: Paused, 3: Out of Recruitment period');
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
        Schema::dropIfExists('works');
    }
}
