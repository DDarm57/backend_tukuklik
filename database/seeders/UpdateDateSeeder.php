<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use App\Models\LpseAccount;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductPhotos;
use App\Models\ProductSku;
use App\Models\ProductTag;
use App\Models\ProductVariants;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared('UPDATE users SET email_verified_at=null, date_of_birth=null, fcm_token=null, remember_token=null, deleted_at=null, avatar="avatar.png", created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE products SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE product_skus SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE product_tags SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE product_variants SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE lpse_account SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE category_product SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE media SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE tags SET created_at=now(), updated_at=now();');
        DB::unprepared('UPDATE product_photos SET created_at=now(), updated_at=now();');
    }
}
