<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('ot_id');
            $table->datetime('clock_in')->nullable();
            $table->datetime('clock_out')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('hour');
            $table->integer('minute');
            $table->string('checked');
            $table->decimal('amount', 10,2)->nullable();
            $table->string('justification')->nullable();
            $table->string('is_manual')->nullable();
            $table->decimal('in_latitude', 9, 6)->default(0.0);
            $table->decimal('in_longitude', 9, 6)->default(0.0);
            $table->decimal('out_latitude', 9, 6)->default(0.0);
            $table->decimal('out_longitude', 9, 6)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_details');
    }
}
