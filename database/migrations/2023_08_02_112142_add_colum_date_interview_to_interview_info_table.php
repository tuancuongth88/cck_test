<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumDateInterviewToInterviewInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interview_infos', function (Blueprint $table) {
            $table->date('date_interview')->nullable()->after('calendar_interview');
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
            $table->dropColumn('date_interview');
        });
    }
}
