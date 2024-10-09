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
        Schema::create('address_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('number');
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_province_id')->nullable();
            $table->string('shipping_city_id')->nullable();
            $table->string('shipping_district_id')->nullable();
            $table->string('shipping_subdistrict_id')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->boolean('bill_to_same_address')->default(1);
            $table->string('billing_name')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_province_id')->nullable();
            $table->string('billing_city_id')->nullable();
            $table->string('billing_district_id')->nullable();
            $table->string('billing_subdistrict_id')->nullable();
            $table->string('billing_postcode')->nullable();
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
        Schema::dropIfExists('address_requests');
    }
};
