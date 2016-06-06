<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Log\Writer as Logger;
use Illuminate\Support\Fluent;

class NotifyFriendsOfRecording extends Job
{
    private $recording;

    public function __construct(Recording $recording)
    {
        $this->recording = $recording;
    }

    public function handle(TwilioClient $twilio, Logger $logger)
    {
        $user = $this->recording->user;

        $text = sprintf(
            "Your friend {$user->name} made PulledOver recording. From %s : %s",
            $this->recording->from,
            $this->recording->url
        );

        $user->friends()->verified()->get()->each(function ($friend) use ($user, $twilio, $text) {
            $twilio->text($friend->number, $text);
        });

        $logger->info('Friends SMS sent: ' . $text);
    }
}
