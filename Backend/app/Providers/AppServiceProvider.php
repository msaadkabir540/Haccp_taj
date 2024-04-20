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
        //

        Validator::extend('employeecode', function ($attribute, $value, $parameters, $validator) {
            // Implement your custom validation logic here
            // You can access the value of the 'employeecode' field using $value
            // Return true if the validation passes, false otherwise
            return true; // Placeholder, replace with your validation logic
        });
        
    }


}
