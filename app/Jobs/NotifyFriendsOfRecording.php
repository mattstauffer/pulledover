<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Log\Writer as Logger;
use Illuminate\Support\Fluent;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;

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

        $user->friends()->verified()->blacklisted(false)->get()->each(function ($friend) use ($user, $twilio, $text) {
            try {
                $twilio->text($friend->number, $text);
            } catch (BlacklistedPhoneNumberException $e) {
                $friend->markBlacklisted();
            }
        });

        $logger->info('Friends SMS sent: ' . $text);
    }
}
