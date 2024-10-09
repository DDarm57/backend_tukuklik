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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('purpose_of');
            $table->integer('subtotal')->default(0);
            $table->integer('tax_amount')->default(0);
            $table->integer('income_tax')->default(0);
            $table->integer('shipping_amount')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('grand_total')->default(0);
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_pic');
            $table->string('user_phone');
            $table->string('merchant_pic')->nullable();
            $table->string('merchant_phone')->nullable();
            $table->string('notes_for_merchant')->nullable();
            $table->string('notes_for_buyer')->nullable();
            $table->enum('payment_type', ['Term Of Payment', 'Cash Before Delivery']);
            $table->integer('termin')->default(0);
            $table->enum('is_merchant_pkp', ['Y','T']);
            $table->dateTime('date');
            $table->dateTime('deadline_date');
            $table->unsignedBigInteger('status');
            $table->string('optional_status')->nullable();
            $table->integer('channel_id');
            $table->foreign('status')->on('status')->references('id');
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
        Schema::dropIfExists('rfq');
    }
};
