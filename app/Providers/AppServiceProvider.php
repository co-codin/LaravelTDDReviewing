<?php

namespace App\Providers;

use View;
use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // View::share('channels', Channel::all());
        View::composer(['threads.create', 'layouts.app'], function($view) {
          $view->with('channels', Channel::all());
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
