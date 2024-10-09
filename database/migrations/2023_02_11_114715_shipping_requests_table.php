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
        Schema::create('shipping_requests', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('address_request_id');
            $table->unsignedBigInteger('shipping_method');
            $table->integer('shipping_fee')->default(0);
            $table->integer('date_estimation')->default(null);
            // $table->string('weight')->default('500')->comment('gm');
            // $table->string('length')->default('30')->comment('cm');
            // $table->string('breadth')->default('20')->comment('cm');
            // $table->string('height')->default('10')->comment('cm');
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
        Schema::dropIfExists('shipping_requests');
    }
};
