<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services_Twilio;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Services_Twilio', function () {
            return new Services_Twilio(
                env('TWILIO_ACCOUNT_SID'),
                env('TWILIO_ACCESS_TOKEN')
            );
        });
    }
}
