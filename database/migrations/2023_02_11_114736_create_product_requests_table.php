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
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_sku_id');
            $table->string('number');
            $table->integer('quantity');
            $table->float('base_price', 16, 2)->default(0);
            $table->float('subtotal', 16, 2)->default(0);
            $table->float('tax_amount', 16, 2)->default(0);
            $table->float('income_tax_amount', 16, 2)->default(0);
            $table->float('total_price', 16, 2)->default(0);
            $table->float('tax_percentage', 16, 2)->default(0);
            $table->float('income_tax_percentage', 16, 2)->default(0);
            $table->string('stock_type');
            $table->integer('processing_estimation');
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
        Schema::dropIfExists('product_requests');
    }
};
