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

    /** @var  User */
    protected $user;

    /** @var  PhoneNumber */
    protected $number;

    /** @var  \App\Recording */
    protected $recording;

    /** @before */
    protected function buildRecording()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class, 'verified')->make();
        $recording = $user->recordings()->save(factory(\App\Recording::class)->make([
            'from' => '+1'.$number->number
        ]));

        $user->phoneNumbers()->save($number);
        $recording->phoneNumber()->associate($number);

        $this->user = $user;
        $this->number = $number;
        $this->recording = $recording;


    }

    public function test_only_verified_friends_are_notified()
    {
        $friend = factory(Friend::class)->make();
        $friendVerified = factory(Friend::class, 'verified')->make();
        $this->user->friends()->saveMany([$friend, $friendVerified]);

        $command = new \App\Jobs\SendNewRecordingNotifications($this->recording);

        $twilioMock = M::spy(TwilioClient::class);
        $this->app->instance(TwilioClient::class, $twilioMock);

        $command->handle(
            $twilioMock,
            app('Illuminate\Log\Writer')
        );

        $twilioMock->shouldHaveReceived('text')->twice()->with(
            M::anyOf($friendVerified->number, $this->number->number),
            M::any()
        );
    }

    public function test_blacklisted_friends_are_not_notified()
    {
        $friendBlackListed = factory(Friend::class, 'verified')->make(['blacklisted' => true]);
        $friendVerified = factory(Friend::class, 'verified')->make();
        $this->user->friends()->saveMany([$friendVerified, $friendBlackListed]);

        $command = new \App\Jobs\SendNewRecordingNotifications($this->recording);

        $twilioMock = M::spy(TwilioClient::class);
        $this->app->instance(TwilioClient::class, $twilioMock);

        $command->handle(
            $twilioMock,
            app('Illuminate\Log\Writer')
        );

        $twilioMock->shouldHaveReceived('text')->twice()->with(
            M::anyOf($friendVerified->number, $this->number->number),
            M::any()
        );
    }

    public function test_blacklisted_friends_get_flagged()
    {
        $friendVerified = factory(Friend::class, 'verified')->make(['number' => '5005550004']);
        $this->user->friends()->saveMany([$friendVerified]);

        $command = new \App\Jobs\SendNewRecordingNotifications($this->recording);

        $command->handle(
            app(TwilioClient::class),
            app('Illuminate\Log\Writer')
        );

        $this->seeInDatabase('friends', ['blacklisted' => 1]);
    }
}
