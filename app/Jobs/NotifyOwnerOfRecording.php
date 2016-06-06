<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Recording;
use App\Phone\TwilioClient;
use Illuminate\Log\Writer as Logger;
use Illuminate\Support\Fluent;

class NotifyOwnerOfRecording extends Job
{
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

        $twilio->text(
            TwilioClient::formatNumberFromTwilio($this->recording->from),
            $text
        );

        $logger->info('Owner SMS sent: ' . $text);
    }
}
