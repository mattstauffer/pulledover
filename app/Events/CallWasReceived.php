<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Support\Facades\Log;

class CallWasReceived extends Event
{
    public $post;

    public function __construct(array $post)
    {
        $this->post = $post;
    }
}

/**
 * Contents of a call received POST from Twilio:
 *
 * [AccountSid] => Long hex
 * [ToZip] =>
 * [FromState] => FL
 * [Called] => +18443116837
 * [FromCountry] => US
 * [CallerCountry] => US
 * [CalledZip] =>
 * [Direction] => inbound
 * [FromCity] => CITY
 * [CalledCountry] => US
 * [CallerState] => FL
 * [CallSid] => Long hex
 * [CalledState] =>
 * [From] => +10987654321
 * [CallerZip] => 12345
 * [FromZip] => 12345
 * [CallStatus] => ringing
 * [ToCity] =>
 * [ToState] =>
 * [To] => +18443116837
 * [ToCountry] => US
 * [CallerCity] => CITY
 * [ApiVersion] => 2010-04-01
 * [Caller] => +10987654321
 * [CalledCity] =>
 */
