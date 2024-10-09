<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DumpUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = file_get_contents(__DIR__.'/user/users.sql');
        $lpse = file_get_contents(__DIR__.'/user/lpse.sql');
        DB::unprepared($users. $lpse);

        $user = User::doesntHave('roles')->get();
        foreach($user as $u) {
            $u->assignRole('Customer');
        }
    }
}
