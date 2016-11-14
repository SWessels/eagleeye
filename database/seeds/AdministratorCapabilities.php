<?php

use Illuminate\Database\Seeder;

class AdministratorCapabilities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=1; $i<=20; $i++) {

            DB::table('capabilities_user')->insert([
                'user_id' => '1',
                'capabilities_id' => $i
            ]);
        }
    }
}
