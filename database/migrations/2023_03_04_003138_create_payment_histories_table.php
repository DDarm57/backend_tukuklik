<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->integer('amount');
            $table->string('evidence')->nullable();
            $table->date('paid_date')->nullable();
            $table->string('status')->nullable();
            $table->text('raw_request')->nullable();
            $table->text('raw_created')->nullable();
            $table->text('raw_updated')->nullable();
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
        Schema::dropIfExists('payment_histories');
    }
};
