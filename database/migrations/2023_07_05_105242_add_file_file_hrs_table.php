<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileFileHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrs', function (Blueprint $table) {
            $table->integer('file_cv_id')->nullable()->after('home_town_other');
            $table->integer('file_job_id')->nullable()->after('file_cv_id');
            $table->text('file_others')->nullable()->after('file_job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrs', function (Blueprint $table) {
            $table->dropColumn(['file_cv_id', 'file_job_id', 'file_others']);
        });
    }
}
