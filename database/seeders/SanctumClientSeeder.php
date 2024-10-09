<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SanctumClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sanctum_clients')->insert([
            'name'          => 'Tukuklik Frontend',
            'client_key'    => Str::random(64),
            'server_key'    => Str::random(64)
        ]);
    }
}
