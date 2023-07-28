<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id')->primary();
            $table->string('admin_name');
            $table->string('admin_user')->uniqid();
            $table->string('admin_pass');
            $table->string('admin_address')->nullable();
            $table->string('admin_phone')->nullable();
            $table->string('admin_facebook')->nullable();
            $table->string('admin_lineid')->nullable();
            $table->string('admin_img')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
