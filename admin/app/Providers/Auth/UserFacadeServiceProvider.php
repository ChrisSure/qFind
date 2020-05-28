<?php

namespace App\Providers\Auth;

use App\Service\Auth\UserGlobalService;
use Illuminate\Support\ServiceProvider;

class UserFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('user', function()
        {
            return new UserGlobalService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
