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
        $provinces = file_get_contents(__DIR__.'/wilayah/provinces.sql');
        $cities = file_get_contents(__DIR__.'/wilayah/cities.sql');
        $districts = file_get_contents(__DIR__.'/wilayah/districts.sql');
        $subdistricts = file_get_contents(__DIR__.'/wilayah/subdistricts.sql');
        // DB::statement("SET GLOBAL max_allowed_packet=4000000;");
        DB::unprepared($provinces.$cities.$districts.$subdistricts);
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
