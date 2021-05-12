<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
       //$this->call(ContactSeeder::class);
       $this->call(AdmAreaSeeder::class);
       $this->call(RegionSeeder::class);
       $this->call(MetroSeeder::class);
      // $this->call(BuildingSeeder::class);
       $this->call(BlockSeeder::class);
    }
}
