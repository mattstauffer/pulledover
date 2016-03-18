<?php

namespace App\Providers;

use App\PhoneNumber;
use App\Phone\TwilioClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Lookups_Services_Twilio;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('valid_phone', function ($attribute, $value, $parameters, $validator) {
            return app(TwilioClient::class)->validatePhone($value);
        });

        Validator::extend('unique_number', function ($attribute, $value, $parameters, $validator) {
            return Auth::user()->phoneNumbers()->where(['number' => $value])->count() === 0;
        });

        Validator::extend('globally_unique_number', function ($attribute, $value, $parameters, $validator) {
            if (PhoneNumber::where(['number' => $value])->count() === 0) {
                return true;
            }

            Log::info(sprintf(
                'User %s tried to add a phone number that already is in use by another user: %s',
                Auth::user() ? Auth::user()->id : 'New user signing up',
                $value
            ));

            return false;
        });

        Validator::extend('unique_friend', function ($attribute, $value, $parameters, $validator) {
            return Auth::user()->friends()->where(['number' => $value])->count() === 0;
        });

        Str::macro('formatNumber', function($number){
            return sprintf(
                '(%s) %s-%s',
                substr($number, 0, 3),
                substr($number, 3, 3),
                substr($number, 6)
            );
        });
    }

    public function register()
    {
        $this->app->singleton('from_number', function($app){
            return Str::formatNumber(
                substr($app[TwilioClient::class]->getFromNumber(), 2)
            );
        });
    }
}
