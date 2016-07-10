<?php

use App\Friend;
use App\Recording;
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

        $twilioMock = M::spy(TwilioClient::class);
        $this->app->instance(TwilioClient::class, $twilioMock);
        $command = new NotifyFriendsOfRecording(
            $user->recordings()->save(factory(Recording::class)->make())
        );

        $command->handle(
            $twilioMock,
            app('Illuminate\Log\Writer')
        );

        $twilioMock->shouldHaveReceived('text')->once()->with(
            $friendVerified->number,
            M::any()
        );
    }
}
