<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('company_name');
            $table->string('company_name_jp');
            $table->integer('major_classification');
            $table->integer('middle_classification');
            $table->string('post_code');
            $table->string('prefectures');
            $table->string('municipality');
            $table->string('number');
            $table->string('other')->nullable();
            $table->string('telephone_number');
            $table->string('mail_address');
            $table->string('url');
            $table->string('job_title');
            $table->string('full_name');
            $table->string('full_name_furigana');
            $table->string('representative_contact')->nullable();
            $table->string('assignee_contact');
            $table->integer('status')->default(EXAMINATION_PENDING);

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('restrict');
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
        Schema::dropIfExists('companies');
    }
}
