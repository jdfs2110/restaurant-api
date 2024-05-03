<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('cocinaobarra', function ($attribute, $value, $parameters, $validator) {
            if (strtolower($value) != 'cocina' && strtolower($value) != 'barra') {
                return false;
            }

            return true;

        }, "El campo debe ser 'cocina' o 'barra'.");
    }
}
