<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class SecureRoutingServiceProvider extends RouteServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind('url', function () {
            $generator = new UrlGenerator(
                App::make('router')->getRoutes(),
                App::make('request'));


        $generator->forceSchema('https');

        return $generator;
    });

    parent::boot();
    }
}
