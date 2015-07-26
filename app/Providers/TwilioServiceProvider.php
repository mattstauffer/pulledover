<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services_Twilio;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_ACCESS_TOKEN');

        $this->app->bind('Services_Twilio', function () use($sid, $token) {
            return new Services_Twilio($sid, $token);
        });
    }
}
