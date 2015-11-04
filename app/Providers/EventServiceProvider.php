<?php

namespace App\Providers;

use App\Events\CallRecordingWasCompleted;
use App\Events\CallWasReceived;
use App\Listeners\MessageOwnerWithRecording;
use App\Listeners\NotifyFriendsCallWasReceived;
use App\Listeners\NotifyFriendsRecordingWasReceived;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CallWasReceived::class => [
            NotifyFriendsCallWasReceived::class,
        ],
        CallRecordingWasCompleted::class => [
            NotifyFriendsRecordingWasReceived::class,
            MessageOwnerWithRecording::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
