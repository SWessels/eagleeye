<?php

use Illuminate\Database\Seeder;

class administrator_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		
		 DB::table('users')->insert([
            'name' => 'Admin',
			'username' =>'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
			'status' => 'active',
			'role_id' => 1
        ]);

    }
}
