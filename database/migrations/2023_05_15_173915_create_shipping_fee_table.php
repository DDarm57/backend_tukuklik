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
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_method_id');
            $table->integer('city_id');
            $table->integer('fee')->default(0);
            $table->integer('minimum_kg')->default(0);
            $table->string('shipping_estimation')->nullable();
            $table->foreign('shipping_method_id')->on('shipping_methods')->references('id');
            $table->foreign('city_id')->on('cities')->references('city_id');
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
        Schema::dropIfExists('shipping_fees');
    }
};
