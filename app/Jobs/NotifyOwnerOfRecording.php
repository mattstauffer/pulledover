<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\Recording;
use Illuminate\Log\Writer as Logger;

class NotifyOwnerOfRecording extends Job
{
    /** @var  Recording */
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
