<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHRsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrs', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id')->nullable();
            $table->integer('hr_organization_id')->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('full_name_ja', 255)->nullable();
            $table->integer('gender')->nullable()->comment('1: Male, 2: Female');
            $table->date('date_of_birth')->nullable();
            $table->integer('work_form')->nullable()->default(1)->comment('1: Full-time, 2: Contract employee, 3: Temporary employee, 4: Others');
            $table->integer('preferred_working_hours')->nullable();
            $table->integer('japanese_level')->nullable()->comment('1: N1, 2: N2, 3: N3, 4: N4, 5: N5, 6: None');
            $table->string('final_education_date', 255)->nullable();
            $table->integer('final_education_classification')->nullable()->comment('1: graduation, 2: finish, 3: dropout');
            $table->integer('final_education_degree')->nullable()->comment('1:Drs, 2:Master, 3:Bachelor, 4:Graduating from junior college, 5:Graduating from vocational school, 6:Graduating from high school');
            $table->integer('major_classification_id')->nullable()->comment('reference job info table');
            $table->integer('middle_classification_id')->nullable()->comment('reference job info table');
            $table->longText('personal_pr_special_notes')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('telephone_number', 255)->nullable();
            $table->string('mobile_phone_number', 255)->nullable();
            $table->string('mail_address', 255)->nullable();
            $table->string('address_city', 255)->nullable();
            $table->string('address_district', 255)->nullable();
            $table->string('address_number', 255)->nullable();
            $table->string('address_other', 255)->nullable();
            $table->string('hometown_city', 255)->nullable();
            $table->string('home_town_district', 255)->nullable();
            $table->string('home_town_number', 255)->nullable();
            $table->string('home_town_other', 255)->nullable();
            $table->integer('status')->default(1)->comment('1: Public, 2: Private, 3: Official offer confirm');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('hrs');
    }
}
