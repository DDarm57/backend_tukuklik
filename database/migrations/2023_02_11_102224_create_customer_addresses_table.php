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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address_name')->nullable();
            $table->string('full_address')->nullable();
            $table->string('shipping_province_id')->nullable();
            $table->string('shipping_city_id')->nullable();
            $table->string('shipping_district_id')->nullable();
            $table->string('shipping_subdistrict_id')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->integer('is_default')->default(0);
            $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->on('users')->references('id');
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
