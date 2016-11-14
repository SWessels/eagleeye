<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Auth;
use View;
use Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        View::composer('*', function($view)
        {
            $view->with('user', Auth::user())->with('domains', Config::get('domains'));
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
