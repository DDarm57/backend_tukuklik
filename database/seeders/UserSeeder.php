<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "id" => 1, 
                "name" => 'Irvan Sulistio' ,
                "email" => 'sulistioirvan@gmail.com',
                "password" => '$2a$12$COHnUJpDtnKTFPVDAmxVe.SjlX1mGuuq0wCNtzjAyveAiuQBDBDfq',
                "is_actived" => 'Y',
                "organization_id" => 1,
                'role_name' => 'Super Administrator' 
            ],
            [
                "id" => 2, 
                "name" => 'Customer 1' ,
                "email" => 'customer@gmail.com',
                "password" => '$2a$12$COHnUJpDtnKTFPVDAmxVe.SjlX1mGuuq0wCNtzjAyveAiuQBDBDfq',
                "is_actived" => 'Y',
                "organization_id" => 1, 
                "role_name" => 'Customer',
            ],
            [
                "id" => 3, 
                "name" => 'Seller 1' ,
                "email" => 'seller@gmail.com',
                "password" => '$2a$12$COHnUJpDtnKTFPVDAmxVe.SjlX1mGuuq0wCNtzjAyveAiuQBDBDfq',
                "is_actived" => 'Y',
                "organization_id" => 1, 
                "role_name" => 'Seller',
            ],
        ];
        foreach($users as $user){
            $roleName = $user['role_name'];
            unset($user['role_name']);
            $user = User::create($user);
            $user->assignRole($roleName);
        }
    }
}
