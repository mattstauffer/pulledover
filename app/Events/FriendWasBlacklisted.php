<?php

namespace App\Events;

use App\Events\Event;
use App\Friend;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FriendWasBlacklisted extends Event
{
    use SerializesModels;

    public $friend;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
        info('friend blacklisted');
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
