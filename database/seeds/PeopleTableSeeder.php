<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('people')->insert([
            [
                'first_name' => 'Вікторія Станіславівна',
                'last_name' => 'Філіпенко',
            ],
            [
                'first_name' => 'ІРИНА МИКОЛАЇВНА',
                'last_name' => 'РАЧОК',
            ],
            [
                'first_name' => 'Володимир Олександрович',
                'last_name' => 'Зеленський',
            ],

        ]);
    }
}
