<?php

namespace App\Providers;

use App\Phone\TwilioClient;
use Illuminate\Support\ServiceProvider;
use Services_Twilio;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Services_Twilio', function ($app) {
            return new Services_Twilio(
                $app->config['services.twilio.sid'],
                $app->config['services.twilio.token']
            );
        });

        $this->app->bind(TwilioClient::class, function ($app) {
            return new TwilioClient(
                $app->config['services.twilio.fromNumber'],
                $app->make('Services_Twilio')
            );
        });
    }
}
