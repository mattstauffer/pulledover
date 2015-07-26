<?php

namespace App\Listeners;

use App\Events\CallWasReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyFriendsCallWasReceived
{
    public function __construct()
    {
        //
    }

    public function handle(CallWasReceived $event)
    {
        // @todo: Notify all friends that this call was received
    }
}
