<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // tooltip
        Blade::directive('tooltip', function ($string) {
            return 'data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="' . $string .'"';
        });

        // inputClass
        Blade::directive('inputClass', function ($string) {
            return 'form-control form-control-xl form-control-outlined';
        });

        // labelClass
        Blade::directive('labelClass', function ($string) {
            return 'form-label-outlined';
        });
    }
}
