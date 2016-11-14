<?php

use Illuminate\Database\Seeder;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_roles')->insert([
            'role_name' => 'administrator'
        ]);

        DB::table('user_roles')->insert([
            'role_name' => 'editor'
        ]);
    }
}
