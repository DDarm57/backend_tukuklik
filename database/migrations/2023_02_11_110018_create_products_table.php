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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("product_name")->nullable();
            $table->unsignedBigInteger("product_type")->nullable()->comment('1 => single_product, 2 => variant_product');
            $table->unsignedBigInteger("unit_type_id")->nullable();
            $table->string("thumbnail_image_source", 255)->nullable();
            // $table->string("barcode_type", 255)->nullable();
            // $table->string("model_number", 255)->nullable();
            // $table->double("shipping_cost", 16,2)->default(0);
            $table->string("discount_type", 50)->nullable();
            $table->double("discount", 16,2)->default(0);
            $table->string("tax_type", 50)->nullable();
            $table->double("tax", 16,2)->default(0);
            $table->string("pdf", 255)->nullable();
            // $table->string("video_provider", 255)->nullable();
            $table->string("video_link", 255)->nullable();
            $table->longText("description")->nullable();
            $table->longText('specification')->nullable();
            $table->Integer("minimum_order_qty")->nullable();
            $table->Integer("max_order_qty")->nullable();
            // $table->string("meta_title", 255)->nullable();
            // $table->longText("meta_description")->nullable();
            // $table->string("meta_image", 255)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger("created_by")->nullable();
            $table->string("slug", 255)->nullable();
            $table->foreign("created_by")->on("users")->references("id")->onDelete("cascade");
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->on("users")->references("id")->onDelete("cascade");
            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->on('merchants')->references('id')->onDelete('cascade');
            $table->enum('stock_type', ['Ready Stock', 'Pre Order'])->default('Ready Stock');
            $table->integer('processing_estimation')->nullable();
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
        Schema::dropIfExists('products');
    }
};
