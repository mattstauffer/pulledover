<?php

namespace App\Listeners;

use App\Events\CallRecordingWasCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageOwnerWithRecording
{
    public function __construct()
    {
        //
    }

    public function handle(CallRecordingWasCompleted $event)
    {
        // @todo: Notify all friends that this voicemail was received
    }
}
