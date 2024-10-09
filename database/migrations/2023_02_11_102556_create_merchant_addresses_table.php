<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('merchant_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address_name')->nullable();
            $table->string('full_address')->nullable();
            $table->integer('province_id');
            $table->integer('city_id');
            $table->integer('district_id');
            $table->bigInteger('subdistrict_id');
            $table->string('postcode')->nullable();
            $table->boolean('is_default')->default(0);
            $table->unsignedBigInteger('merchant_id');
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
        Schema::dropIfExists('pickup_locations');
    }
};
