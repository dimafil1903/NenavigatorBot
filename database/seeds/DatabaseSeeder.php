<?php

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
        $this->call(DeputiesTableSeeder::class);
        $this->call(LocalityTableSeeder::class);
        $this->call(DeputyLocalityTableSeeder::class);

    }
}
