<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Recording;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\TwilioClient;
use Illuminate\Log\Writer as Logger;
use Illuminate\Queue\SerializesModels;

class NotifyOwnerOfRecording extends Job
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
            "New Pulledover.us recording. Number: %s\nFrom: %s %s\nURL: %s \n .",
            $this->recording->from,
            $this->recording->city,
            $this->recording->state,
            $this->recording->url
        );

        try {
            $twilio->text(
                TwilioClient::formatNumberFromTwilio($this->recording->from),
                $text
            );

            $logger->info('Owner SMS sent: ' . $text);
        } catch (BlacklistedPhoneNumberException $e) {
            $this->recording->phoneNumber()->addToBlacklist();
        }
    }
}
