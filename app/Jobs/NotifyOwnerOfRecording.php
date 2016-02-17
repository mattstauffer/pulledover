<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\TwilioClient;
use Illuminate\Contracts\Bus\SelfHandling;

class NotifyOwnerOfRecording extends Job implements SelfHandling
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle(TwilioClient $twilio)
    {
        $twilio->text(
            TwilioClient::formatNumberFromTwilio($this->request->get("From")),
            sprintf(
                "New Pulledover.us recording. Number: %s\nFrom: %s %s\nURL: %s",
                $this->request->get("From"),
                $this->request->get("CallerCity"),
                $this->request->get("CallerState"),
                $this->request->get("RecordingUrl")
            )
        );
    }
}
