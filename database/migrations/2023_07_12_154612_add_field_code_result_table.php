<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCodeResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->string('code')->nullable()->after('work_id');
            $table->renameColumn('status_period', 'time');
            $table->renameColumn('remark', 'note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->renameColumn('time', 'status_period');
            $table->renameColumn('note', 'remark');
        });
    }
}
