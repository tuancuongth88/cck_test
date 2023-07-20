<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInforCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('establishment_year')->nullable()->after('assignee_contact');
            $table->string('startup_year')->nullable()->after('establishment_year');
            $table->string('capital')->nullable()->after('startup_year');
            $table->string('proceeds')->nullable()->after('capital');
            $table->string('number_of_staffs')->nullable()->after('proceeds');
            $table->string('number_of_operations')->nullable()->after('number_of_staffs');
            $table->string('number_of_shops')->nullable()->after('number_of_operations');
            $table->string('number_of_factory')->nullable()->after('number_of_shops');
            $table->string('fiscal')->nullable()->after('number_of_factory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['establishment_year','startup_year','capital','proceeds','number_of_staffs','number_of_operations','number_of_shops','number_of_factory','fiscal']);
        });
    }
}
