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
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id")->nullable();
            $table->string("sku", 250)->nullable();
            $table->double("selling_price", 16,2)->default(0);
            // $table->string('variant_image')->nullable();
            $table->boolean("status")->default(1);
            $table->unsignedInteger('product_stock')->default(0);
            $table->string('track_sku',250)->nullable();
            $table->string('weight')->default('500')->comment('gm');
            $table->string('length')->default('30')->comment('cm');
            $table->string('breadth')->default('20')->comment('cm');
            $table->string('height')->default('10')->comment('cm');
            $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');
            $table->enum('is_primary', ['Y', 'T'])->default('T');
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
        Schema::dropIfExists('product_skus');
    }
};
