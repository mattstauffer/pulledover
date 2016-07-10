<?php

namespace App\Listeners;

use App\Events\FriendWasBlacklisted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOwnerFriendWasBlacklisted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FriendWasBlacklisted  $event
     * @return void
     */
    public function handle(FriendWasBlacklisted $event)
    {
        // todo send owner notification
    }
}
