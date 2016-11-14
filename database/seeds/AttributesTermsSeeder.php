<?php

use Illuminate\Database\Seeder;

class AttributesTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $term[] = array(
            'name'	        =>		'XSmall',
            'slug'		    =>		'xsmall',
            'attributes_id'	=>		'1'
        );

        $term[] = array(
            'name'	        =>		'Small',
            'slug'		    =>		'small',
            'attributes_id'	=>		'1'
        );
        $term[] = array(
            'name'	        =>		'Medium',
            'slug'		    =>		'medium',
            'attributes_id'	=>		'1'
        );
        $term[] = array(
            'name'	        =>		'Large',
            'slug'		    =>		'large',
            'attributes_id'	=>		'1'
        );
        $term[] = array(
            'name'	        =>		'Xlarge',
            'slug'		    =>		'xlarge',
            'attributes_id'	=>		'1'
        );
        $term[] = array(
            'name'	        =>		'XXlarge',
            'slug'		    =>		'xxlarge',
            'attributes_id'	=>		'1'
        );


        $term[] = array(
            'name'	        =>		'36',
            'slug'		    =>		'36',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'37',
            'slug'		    =>		'37',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'38',
            'slug'		    =>		'38',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'39',
            'slug'		    =>		'39',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'40',
            'slug'		    =>		'40',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'41',
            'slug'		    =>		'41',
            'attributes_id'	=>		'2'
        );
        $term[] = array(
            'name'	        =>		'X/S',
            'slug'		    =>		'x/s',
            'attributes_id'	=>		'3'
        );
        $term[] = array(
            'name'	        =>		'S/M',
            'slug'		    =>		's/m',
            'attributes_id'	=>		'3'
        );
        $term[] = array(
            'name'	        =>		'M/L',
            'slug'		    =>		'm/l',
            'attributes_id'	=>		'3'
        ); 
        $term[] = array(
            'name'	        =>		'L/XL',
            'slug'		    =>		'l/xl',
            'attributes_id'	=>		'3'
        );
        $term[] = array(
            'name'	        =>		'Bordeaux',
            'slug'		    =>		'bordeaux',
            'attributes_id'	=>		'4'
        );
        $term[] = array(
            'name'	        =>		'Creme',
            'slug'		    =>		'Creme',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Legergroen',
            'slug'		    =>		'legergroen',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Marineblauw',
            'slug'		    =>		'marineblauw',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Taupe',
            'slug'		    =>		'taupe',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Zwart',
            'slug'		    =>		'zwart',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'antraciet',
            'slug'		    =>		'antraciet',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Babyblauw',
            'slug'		    =>		'babyblauw',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Beige',
            'slug'		    =>		'beige',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Blauw',
            'slug'		    =>		'blauw',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Bruin',
            'slug'		    =>		'bruin',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Geel',
            'slug'		    =>		'geel',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Kobaltblauw',
            'slug'		    =>		'kobaltblauw',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Koraalrood',
            'slug'		    =>		'koraalrood',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'roze',
            'slug'		    =>		'roze',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'mintgroen',
            'slug'		    =>		'mintgroen',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Yellow',
            'slug'		    =>		'yellow',
            'attributes_id'	=>		'4'
        );

        $term[] = array(
            'name'	        =>		'Wit',
            'slug'		    =>		'wit',
            'attributes_id'	=>		'4'
        );


        $term[] = array(
            'name'	        =>		'Zalmroze',
            'slug'		    =>		'Zalmroze',
            'attributes_id'	=>		'4'
        );


      foreach($term as $term)
      {
          DB::table('terms')->insert($term);
      }
    }
}
