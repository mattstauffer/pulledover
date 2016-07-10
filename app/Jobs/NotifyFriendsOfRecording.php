<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Log\Writer as Logger;
use Illuminate\Queue\SerializesModels;

class NotifyFriendsOfRecording extends Job
{
    use SerializesModels;

    private $recording;

    public function __construct(Recording $recording)
    {
        $this->recording = $recording;
    }

    public function handle(TwilioClient $twilio, Logger $logger)
    {
        $text = sprintf(
            "Your friend %s made PulledOver recording. From %s : %s",
            $this->recording->user->name,
            $this->recording->from,
            $this->recording->url
        );

        $this->getNotifiableFriends()->each(function ($friend) use ($twilio, $text) {
            try {
                $twilio->text($friend->number, $text);
            } catch (BlacklistedPhoneNumberException $e) {
                $friend->addToBlacklist();
            }
        });

        $logger->info('Friends SMS sent: ' . $text);
    }

    public function getNotifiableFriends()
    {
        return $this->recording->user->friends()->verified()->blacklisted(false)->get();
    }
}
