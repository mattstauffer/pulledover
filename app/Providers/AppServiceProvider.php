<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('unique_number', function($attribute, $value, $parameters, $validator) {
            return Auth::user()->phoneNumbers()->where(['number' => $value])->count() === 0;
        });

        Validator::extend('unique_friend', function($attribute, $value, $parameters, $validator) {
            return Auth::user()->friends()->where(['number' => $value])->count() === 0;
        });
    }

    public function register()
    {
        //
    }
}
