<?php

use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_only_verified_friends_are_notified()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($number);

        $friend = factory(Friend::class)->make();
        $user->friends()->save($friend);

        $request = new \Illuminate\Http\Request([
            'From' => 'abc',
            'CallerCity' => 'def',
            'CallerState' => 'ghi',
            'RecordingUrl' => 'jkl',
        ]);

        $twilioMock = m::mock(); // @todo

        $command = new NotifyFriendsOfRecording($request);

        $command->handle(
            $twilioMock,
            app('Illuminate\Log\Writer')
        );

        // Assert stuff
    }
}
