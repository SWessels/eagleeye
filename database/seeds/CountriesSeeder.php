<?php

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'code'  => 'BL',
            'name'  => 'Belgium'
        ]);

        DB::table('countries')->insert([
            'code'  => 'NL',
            'name'  => 'Netherlands'
        ]);
    }
}
