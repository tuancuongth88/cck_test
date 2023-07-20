<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_id');
            $table->integer('work_id');
            $table->string('code', 255)->nullable();
            $table->date('interview_date')->nullable();
            $table->integer('type')->nullable()->comment('1: group, 2: private');
            $table->integer('status_selection')->default(1)->comment('1: Document passing, 2: Offer confirm, 3: First pass, 4: Second pass, 5: Third pass, 6: Fourth pass, 7: Fifth pass, 8: Interview cancel, 9: Interview decline');
            $table->integer('status_interview_adjustment')->default(1)->comment('1: Before adjustment, 2: Adjusting, 3: URL setting, 4: Adjusted');
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('interviews');
    }
}
