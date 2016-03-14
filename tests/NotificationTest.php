<?php

use App\Friend;
use App\Jobs\NotifyFriendsOfRecording;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Mockery as M;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_only_verified_friends_are_notified()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class, 'verified')->make();
        $user->phoneNumbers()->save($number);

        $friend = factory(Friend::class)->make();
        $friendVerified = factory(Friend::class, 'verified')->make();
        $user->friends()->saveMany([$friend, $friendVerified]);

        $recording = $user->recordings()->save(factory(\App\Recording::class)->make());
        $recording->phone_number()->associate($number);

        $twilioMock = M::spy(TwilioClient::class);
        $this->app->instance(TwilioClient::class, $twilioMock);
        $command = new \App\Jobs\SendNewRecordingNotifications($recording);

        $command->handle(
            $twilioMock,
            app('Illuminate\Log\Writer')
        );

        \Log::info([$number->number, $friendVerified->number, $friend->number]);
        $twilioMock->shouldHaveReceived('text')->atLeast()->once()->with(
            $number->number,
            M::any()
        );
    }
}
