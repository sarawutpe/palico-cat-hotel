<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id('member_id')->primary();
            $table->string('member_name');
            $table->string('member_user')->uniqid();
            $table->string('member_pass');
            $table->string('member_address')->nullable();
            $table->string('member_phone')->nullable();
            $table->string('member_facebook')->nullable();
            $table->string('member_lineid')->nullable();
            $table->string('member_img')->nullable();
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
        Schema::dropIfExists('members');
    }
}
