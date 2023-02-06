<?php

use Database\Seeders\WorldSeeder;
use Database\Seeders\WorldStatusSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(WorldSeeder::class);
//        $this->call(WorldStatusSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(TopicCategoryTableSeeder::class);
        $this->call(TopicTableSeeder::class);
    }
}
