<?php

namespace App\Providers;

use App\Phone\TwilioClient;
use Illuminate\Support\ServiceProvider;
use Lookups_Services_Twilio;
use Services_Twilio;
use Services_Twilio_TinyHttp;
use Services_Twilio_RequestValidator;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Services_Twilio', function ($app) {
//            return new Services_Twilio(
//                $app->config['services.twilio.sid'],
//                $app->config['services.twilio.token']
//            );

            // Try to override SSL cert issue
            $http = new Services_Twilio_TinyHttp(
                'https://api.twilio.com',
                array('curlopts' => array(
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ))
            );

            return new Services_Twilio(
                $app->config['services.twilio.sid'],
                $app->config['services.twilio.token'],
                "2010-04-01",
                $http
            );
        });

        $this->app->bind('Lookups_Services_Twilio', function ($app) {
            return new Lookups_Services_Twilio(
                $app->config['services.twilio.sid'],
                $app->config['services.twilio.token'],
                'v1'
            );
        });

        $this->app->bind(TwilioClient::class, function ($app) {
            return new TwilioClient(
                $app->config['services.twilio.fromNumber'],
                $app->make('Services_Twilio')
            );
        });

        $this->app->bind(Services_Twilio_RequestValidator::class, function ($app) {
            return new Services_Twilio_RequestValidator(
                 $app->config['services.twilio.token']
            );
        });
    }
}
