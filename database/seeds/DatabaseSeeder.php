<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Seeds;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		
        $this->call(CapabilitiesTableSeeder::class);
        $this->call(AdministratorCapabilities::class);
        $this->call(RolesSeed::class);
		$this->call(administrator_user::class);
		$this->call(AttributesSeeder::class);
        $this->call(AttributesTermsSeeder::class);
        $this->call(CountriesSeeder::class);
    }
}
