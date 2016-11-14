<?php

namespace App\Providers;

use App\User;
use App\Products;
use APP\UserCapabilities;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Products' => 'App\Policies\ProductsPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //

        $gate->define('admin-access', function($user){
            return $user->role() == 'administrator';
        });

        $gate->define('editor-access', function($user){
            return $user->role() == 'editor';
        });


    }
}