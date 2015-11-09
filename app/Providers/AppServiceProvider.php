<?php

namespace App\Providers;

use App\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('unique_number', function($attribute, $value, $parameters, $validator) {
            return Auth::user()->phoneNumbers()->where(['number' => $value])->count() === 0;
        });

        Validator::extend('globally_unique_number', function($attribute, $value, $parameters, $validator) {
            if (PhoneNumber::where(['number' => $value])->count() > 0) {
                Log::info(sprintf(
                    'User %s tried to add a phone number that already is in use by another user: %s',
                    Auth::user()->id,
                    $value
                ));
                return false;
            }

            return true;
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
