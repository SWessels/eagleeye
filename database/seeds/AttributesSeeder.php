<?php

use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		
		$attributes1 = array(
								'name'		=>		'Size',
								'slug'		=>		'size',
								'type'		=>		'default'
						   );
						   
		$attributes2 = array(
								'name'		=>		'Shoe Size',
								'slug'		=>		'shoe-size',
								'type'		=>		'default'
						   );
						   
						   
		$attributes3 = array(
								'name'		=>		'Size Combination',
								'slug'		=>		'shoe-combination',
								'type'		=>		'default'
						   );
						   				   				   
		$attributes4 = array(
								'name'		=>		'Color',
								'slug'		=>		'color',
								'type'		=>		'default'
						   );				   
						   
		DB::table('productattributes')->insert($attributes1);
		DB::table('productattributes')->insert($attributes2);
        DB::table('productattributes')->insert($attributes3);
		DB::table('productattributes')->insert($attributes4);
    }
}
