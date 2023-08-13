<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_receipts', function (Blueprint $table) {
            $table->id('pay_receipt_id');
            $table->unsignedBigInteger('rent_id');
            $table->timestamp('pay_receipt_datetime');
            $table->string('pay_receipt_qr');
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
        Schema::dropIfExists('pay_receipts');
    }
}
