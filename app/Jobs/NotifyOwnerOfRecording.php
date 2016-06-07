<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use Illuminate\Log\Writer as Logger;

class NotifyOwnerOfRecording extends Job
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle(TwilioClient $twilio, Logger $logger)
    {
        $text = sprintf(
            "New Pulledover.us recording. Number: %s\nFrom: %s %s\nURL: %s .",
            $this->request->get("From"),
            $this->request->get("CallerCity"),
            $this->request->get("CallerState"),
            $this->request->get("RecordingUrl")
        );

        $twilio->text(
            TwilioClient::formatNumberFromTwilio($this->request->get("From")),
            $text
        );

        $logger->info('Owner SMS sent: ' . $text);
    }
}
