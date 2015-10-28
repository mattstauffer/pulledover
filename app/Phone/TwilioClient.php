<?php

namespace App\Phone;

use Services_Twilio;

class TwilioClient
{
    private $twilio;

    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    public function text($number, $message)
    {
        return $this->twilio->account->messages->sendMessage(
            env('TWILIO_FROM_NUMBER'),
            $number,
            $message
        );
    }
}
