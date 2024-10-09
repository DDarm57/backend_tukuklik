<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DumpUserSeeder::class,
            DumpProductsSeeder::class,
            SanctumClientSeeder::class,
            UpdateDateSeeder::class,
            // AreaSeeder::class,
        ]);
    }
}
