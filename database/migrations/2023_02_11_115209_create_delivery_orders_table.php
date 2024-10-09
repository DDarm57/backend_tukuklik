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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('do_number');
            $table->string('order_number');
            $table->string('delivery_number');
            $table->string('delivery_recipient_name');
            $table->date('delivery_date');
            $table->date('date_estimation');
            $table->date('delivered_date')->nullable();
            $table->unsignedBigInteger('shipping_request_id');
            $table->unsignedBigInteger('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('status')->on('status')->references('id');
            $table->foreign('shipping_request_id')->on('shipping_requests')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_orders');
    }
};
