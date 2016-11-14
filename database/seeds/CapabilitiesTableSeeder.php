<?php

use Illuminate\Database\Seeder;

class CapabilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('capabilities')->insert([
            'capability' => 'themusthaves.nl'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'themusthaves.de'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'musthavesforreal.com'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'posts'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'pages'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'products'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'orders'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'display'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'product_feeds'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'invoices'
        ]);


        DB::table('capabilities')->insert([
            'capability' => 'settings'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'tabs'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'display'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'plugins'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'employees'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'customers'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'revenue_report'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'sale_report'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'customer_report'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'stock_report'
        ]);

        DB::table('capabilities')->insert([
        'capability' => 'media'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'menu'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'coupons'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'sliders'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'category_drag_drop'
        ]);


        DB::table('capabilities')->insert([
            'capability' => 'seo_general'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'sitemap'
        ]);


        DB::table('capabilities')->insert([
            'capability' => 'desktop_banners'
        ]);

        DB::table('capabilities')->insert([
            'capability' => 'mobile_banners'
        ]);


        DB::table('capabilities')->insert([
            'capability' => 'pricing'
        ]);



    }
}
