<?php

namespace App\Providers;
use App\Channel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
     // \View::composer('threads.create', function($view){
          \View::composer('*', function($view){

              $channels = \Cache::rememberForever('channels', function () {
                  return Channel::all();
              });
              $view->with('channels',$channels);
          });

          Validator::extend('spamfree','App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);

    }
}
