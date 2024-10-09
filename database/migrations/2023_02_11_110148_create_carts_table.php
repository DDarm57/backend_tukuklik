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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_sku_id');
            $table->unsignedInteger('qty')->default(1);
            $table->float('base_price', 16, 2)->default(0);
            $table->float('subtotal', 16, 2)->default(0);
            $table->float('tax_amount', 16, 2)->default(0);
            $table->float('total_price',16, 2)->default(0);
            $table->timestamps();
            // $table->foreign('product_sku_id')->on('product_skus')->references('id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
