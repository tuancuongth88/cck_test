<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumTypeToInterviewInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interview_infos', function (Blueprint $table) {
            $table->integer('type')->nullable()->comment('1.group|2.private')->after('interview_id');
            $table->integer('number')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interview_infos', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('number');
        });
    }
}
