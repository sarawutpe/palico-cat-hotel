<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id('rent_id');
            $table->string('rent_datetime');
            $table->string('rent_status');
            $table->string('rent_price');
            $table->string('in_datetime');
            $table->string('out_datetime');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('employee_in');
            $table->unsignedBigInteger('employee_pay');
            $table->unsignedBigInteger('room_id');
            $table->string('pay_status');
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
        Schema::dropIfExists('rents');
    }
}
