<?php

use Illuminate\Database\Seeder;

class DeputyLocalityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        \App\DeputyLocality::create([
            'deputy_id' => 1,
            'county_id' => 1
            //      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 2,
            'county_id' => 1
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 3,
            'county_id' => 2
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 4,
            'county_id' => 3
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 5,
            'county_id' => 3
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 6,
            'county_id' => 4
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 7,
            'county_id' => 5
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 8,
            'county_id' => 5
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 9,
            'county_id' => 6
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 10,
            'county_id' => 7
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 11,
            'county_id' => 8
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 11,
            'county_id' => 9
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 12,
            'county_id' => 9
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 13,
            'county_id' => 11
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 14,
            'county_id' => 12
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 15,
            'county_id' => 13
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 16,
            'county_id' => 14
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 17,
            'county_id' => 15
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 18,
            'county_id' => 16
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 19,
            'county_id' => 16
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 20,
            'county_id' => 17
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 21,
            'county_id' => 18
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 22,
            'county_id' => 19
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 23,
            'county_id' => 21
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 24,
            'county_id' => 23
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 25,
            'county_id' => 24
        ])->save();
        \App\DeputyLocality::create([
            'deputy_id' => 26,
            'county_id' => 25
        ])->save();

        \App\DeputyLocality::create([
            'deputy_id' => 20,
            'county_id' => 26
        ])->save();


    }
}
