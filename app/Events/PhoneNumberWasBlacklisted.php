<?php

namespace App\Events;

use App\Events\Event;
use App\PhoneNumber;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneNumberWasBlacklisted extends Event
{
    use SerializesModels;

    public $phoneNumber;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
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
