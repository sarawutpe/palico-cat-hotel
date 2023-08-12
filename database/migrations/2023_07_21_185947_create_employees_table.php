<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_name');
            $table->string('employee_user')->uniqid();
            $table->string('employee_pass')->nullable();
            $table->string('employee_email')->uniqid();
            $table->string('employee_address')->nullable();
            $table->string('employee_phone')->nullable();
            $table->string('employee_facebook')->nullable();
            $table->string('employee_lineid')->nullable();
            $table->string('employee_img')->nullable();
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
        Schema::dropIfExists('emps');
    }
}
