<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_organization', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('corporate_name_en');
            $table->string('corporate_name_ja');
            $table->string('license_no');
            $table->integer('account_classification')->comment('1:Own platform, 2:Sending agency, 3:Dispatch agency, 4:School');
            $table->integer('country')->nullable();
            $table->string('representative_full_name');
            $table->string('representative_full_name_furigana');
            $table->string('representative_contact')->nullable();
            $table->string('assignee_contact');
            $table->string('post_code', 10);
            $table->string('prefectures');
            $table->string('municipality');
            $table->string('number');
            $table->string('other')->nullable();
            $table->string('mail_address');
            $table->string('url');
            $table->integer('certificate_file_id');
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
        Schema::dropIfExists('hr_organization');
    }
}
