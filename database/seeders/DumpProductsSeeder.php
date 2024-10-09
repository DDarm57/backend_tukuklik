<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DumpProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = file_get_contents(__DIR__.'/product/units.sql');
        $attributes = file_get_contents(__DIR__.'/product/attributes.sql');
        $attribute_values = file_get_contents(__DIR__.'/product/attribute_values.sql');
        $categories = file_get_contents(__DIR__.'/product/categories.sql');
        $category_image = file_get_contents(__DIR__.'/product/category_image.sql');
        $media = file_get_contents(__DIR__.'/product/media.sql');
        $tags = file_get_contents(__DIR__.'/product/tags.sql');

        $products = file_get_contents(__DIR__.'/product/products.sql');
        $product_tags = file_get_contents(__DIR__.'/product/product_tags.sql');
        $product_photos = file_get_contents(__DIR__.'/product/product_photos.sql');
        $product_skus = file_get_contents(__DIR__.'/product/product_skus.sql');
        $product_variants = file_get_contents(__DIR__.'/product/product_variants.sql');
        $category_product = file_get_contents(__DIR__.'/product/category_product.sql');

        // DB::statement("SET GLOBAL max_allowed_packet=4000000;");
        DB::unprepared(
            $units .
            $attributes .
            $attribute_values .
            $categories .
            $category_image . 
            $media .
            $tags .

            $products .
            $product_tags .
            $product_photos .
            $product_skus .
            $product_variants .
            $category_product
        );
    }
}
