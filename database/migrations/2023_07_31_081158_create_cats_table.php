<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id('cat_id');
            $table->string('cat_name');
            $table->string('cat_age')->uniqid();
            $table->string('cat_sex')->nullable();
            $table->string('cat_color')->nullable();
            $table->string('cat_gen')->nullable();
            $table->string('cat_ref')->nullable();
            $table->string('cat_img')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
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
        Schema::dropIfExists('cats');
    }
}
