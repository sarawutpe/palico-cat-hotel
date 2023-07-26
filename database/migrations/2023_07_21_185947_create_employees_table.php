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
            $table->id('emp_id');
            $table->string('emp_name');
            $table->string('emp_user')->uniqid();
            $table->string('emp_pass');
            $table->string('emp_address')->nullable();
            $table->string('emp_phone')->nullable();
            $table->string('emp_facebook')->nullable();
            $table->string('emp_lineid')->nullable();
            $table->string('emp_img')->nullable();
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
