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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('quotation_number');
            $table->unsignedBigInteger('order_status');
            $table->integer('subtotal')->default(0);
            $table->integer('tax_amount')->default(0);
            $table->integer('income_tax')->default(0);
            $table->integer('shipping_amount')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('grand_total')->default(0);
            $table->dateTime('purchase_date');
            $table->dateTime('purchase_deadline_date');
            $table->dateTime('order_shipped_estimation')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('channel_id');
            $table->foreign('order_status')->on('status')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('merchant_id')->on('merchants')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
