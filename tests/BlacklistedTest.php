<?php

use App\User;
use App\Friend;
use App\PhoneNumber;
use App\Recording;
use App\Phone\TwilioClient;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Jobs\NotifyFriendsOfRecording;
use App\Jobs\NotifyOwnerOfRecording;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as M;

class BlacklistedTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Disable the twilio validator so we dont need so sign the requests
     * 
     * @before
     */
    public function disableRequestValidator()
    {
        $this->afterApplicationCreated(function () {

            // disable request validator
            $this->app->bind('Services_Twilio_RequestValidator', function ($app) {
                $mock = M::mock('Services_Twilio_RequestValidator');
                $mock->shouldReceive('validate')->andReturn(true);

                return $mock;
            });
        });
    }

    public function test_it_marks_blacklisted_true_when_stop_text_received()
    {
        list($number, $friend) = $this->createNumberAndFriend();

        $this->receiveText($friend, 'STOP');
        $this->receiveText($number, 'STOP');

        $this->assertBlacklisted($number);
        $this->assertBlacklisted($friend);
    }

    public function test_it_marks_blacklisted_false_when_start_text_received()
    {
        list($number, $friend) = $this->createNumberAndFriend(['is_blacklisted' => true]);

        $this->receiveText($friend, 'START');
        $this->receiveText($number, 'START');

        $this->assertBlacklisted($number, false);
        $this->assertBlacklisted($friend, false);
    }

    public function test_it_marks_blacklisted_if_exception_is_thrown()
    {
        list($number, $friend) = $this->createNumberAndFriend();
        $recording = factory(Recording::class)->make(['from' => $number->number]);
        $number->user->recordings()->save($recording);

        $twilioMock = M::mock(TwilioClient::class);
        $twilioMock->shouldReceive('text')->andThrow(BlacklistedPhoneNumberException::class);
        $this->app->instance(TwilioClient::class, $twilioMock);

        dispatch(new NotifyFriendsOfRecording($recording));
        dispatch(new NotifyOwnerOfRecording($recording));
        
        $this->assertBlacklisted($number);
        $this->assertBlacklisted($friend);
    }

    public function assertBlacklisted($model, $value = true)
    {
        $this->seeInDatabase($model->getTable(), [
            'id' => $model->getKey(),
            'is_blacklisted' => $value
        ]);
    }

    public function createNumberAndFriend($attributes = [])
    {
        $user = factory(User::class)->create();
        $phoneNumber = factory(PhoneNumber::class, 'verified')->make($attributes);
        $friend = factory(Friend::class, 'verified')->make($attributes);

        $user->phoneNumbers()->save($phoneNumber);
        $user->friends()->save($friend);

        return [$phoneNumber, $friend];
    }

    /** Send an incoming text from $model */
    public function receiveText($model, $message)
    {
        $this->post('receive-text', [
            'From' => '+1' . $model->number,
            'Body' => $message
        ]);

        $this->assertResponseOk();

        return $this;
    }
}
