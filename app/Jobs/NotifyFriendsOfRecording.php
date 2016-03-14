<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Log\Writer as Logger;

class NotifyFriendsOfRecording extends Job
{

    /**
     * @var Recording
     */
    private $recording;

    public function __construct(Recording $recording)
    {
        $this->recording = $recording;
    }

    public function handle(TwilioClient $twilio, Logger $logger)
    {
        $user = $this->recording->user;

        $text = sprintf(
            "Your friend {$user->name} has completed a PulledOver recording. From %s, City %s, State %s, Recording %s",
            $this->recording->from,
            $this->recording->city,
            $this->recording->state,
            $this->recording->url
        );

        $user->friends()->verified()->get()->each(function ($friend) use ($user, $twilio, $text) {
            $twilio->text($friend->number, $text);
        });

        $logger->info('Friends SMS sent: ' . $text);
    }
}
