<?php

namespace Database\Seeders;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1, 
                'org_name' =>'Chief Executive Officer', 
                'org_type' => 'BOD',
                'parent_org_id' => NULL,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id' => 2, 
                'org_name' =>'Chief Technology Officer', 
                'org_type' => 'BOD',
                'parent_org_id' => NULL,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id' => 3, 
                'org_name' =>'Chief Financial Officer', 
                'org_type' => 'BOD',
                'parent_org_id' => NULL,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id' => 4, 
                'org_name' =>'Accounting', 
                'org_type' => 'Department',
                'parent_org_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'id' => 5, 
                'org_name' =>'IT Apps', 
                'org_type' => 'Department',
                'parent_org_id' => 2,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        Organization::insert($data);
    }
}
