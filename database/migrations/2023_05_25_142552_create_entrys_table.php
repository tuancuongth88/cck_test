<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_code', 255)->nullable();
            $table->date('request_date')->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('work_id')->nullable();
            $table->integer('status')->nullable()->comment('1: Requesting, 2: Reject, 3: Decline, 4: Other company official offer');
            $table->longText('remarks')->nullable();
            $table->integer('why_reject')->nullable();
            $table->text('other_note')->nullable();
            $table->enum('display', ['on', 'off'])->default('on');
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
        Schema::dropIfExists('entries');
    }
}
