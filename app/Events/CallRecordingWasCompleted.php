<?php

namespace App\Events;

use App\Events\Event;

class CallRecordingWasCompleted extends Event
{
    public $post;

    public function __construct(array $post)
    {
        $this->post = $post;
    }
}

/**
 * Call recording completed POST from Twilio:
 *
 * [AccountSid] => Long hex thing
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
 * [CallSid] => Long hex thing
 * [CalledState] =>
 * [From] => +10987654321
 * [CallerZip] => 12345
 * [FromZip] => 12345
 * [CallStatus] => completed
 * [ToCity] =>
 * [ToState] =>
 * [RecordingUrl] => http://api.twilio.com/2010-04-01/Accounts/Long hex/Recordings/Long hex
 * [To] => +18443116837
 * [Digits] => hangup
 * [ToCountry] => US
 * [RecordingDuration] => 6
 * [CallerCity] => CITY
 * [ApiVersion] => 2010-04-01
 * [Caller] => +10987654321
 * [CalledCity] =>
 * [RecordingSid] => Long hex
 */
