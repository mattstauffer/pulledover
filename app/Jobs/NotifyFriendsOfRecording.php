<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use Illuminate\Contracts\Bus\SelfHandling;

class NotifyFriendsOfRecording extends Job implements SelfHandling
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle(TwilioClient $twilio)
    {
        $user = PhoneNumber::findByTwilioNumber($this->request->get('From'))->user;

        $user->friends->each(function ($friend) use ($user, $twilio) {
            $twilio->text(
                $friend->number,
                sprintf(
                    "Your friend {$user->name} has completed a PulledOver recording. From %s, City %s, State %s, Recording %s",
                    $this->request->get("From"),
                    $this->request->get("CallerCity"),
                    $this->request->get("CallerState"),
                    $this->request->get("RecordingUrl")
                )
            );
        });
    }
}
