<?php

use App\Friend;
use App\PhoneNumber;
use App\Phone\TwilioClient;
use App\Recording;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class RecordingTest extends TestCase
{
    use DatabaseMigrations;

    private $callPost = [
        'AccountSid' => '290t5102934j1234',
        'ToZip' => '',
        'FromState' => 'FL',
        'Called' => '+18443116837',
        'FromCountry' => 'US',
        'CallerCountry' => 'US',
        'CalledZip' => '',
        'Direction' => 'inbound',
        'FromCity' => 'CITY',
        'CalledCountry' => 'US',
        'CallerState' => 'FL',
        'CallSid' => '0n9213949012341',
        'CalledState' => 'ZZZ',
        'From' => '+17345780309',
        'CallerZip' => '12345',
        'FromZip' => '12345',
        'CallStatus' => 'ringing',
        'ToCity' => '',
        'ToState' => '',
        'To' => '+18443116837',
        'ToCountry' => 'US',
        'CallerCity' => 'CITY',
        'ApiVersion' => '2010-04-01',
        'Caller' => '+17345780309',
        'CalledCity' => '',
    ];

    private $afterCallPost = [
        'AccountSid' => '14k1203k4-0jfjsoqwer',
        'ToZip' => '',
        'FromState' => 'FL',
        'Called' => '+18443116837',
        'FromCountry' => 'US',
        'CallerCountry' => 'US',
        'CalledZip' => '',
        'Direction' => 'inbound',
        'FromCity' => 'CITY',
        'CalledCountry' => 'US',
        'CallerState' => 'FL',
        'CallSid' => 'pkp0fj09123409h1234',
        'CalledState' => '',
        'From' => '+17346825309',
        'CallerZip' => '12345',
        'FromZip' => '12345',
        'CallStatus' => 'completed',
        'ToCity' => 'Z',
        'ToState' => '',
        'RecordingUrl' => 'http://api.twilio.com/2010-04-01/Accounts/Longhex/Recordings/Longhex',
        'To' => '+18443116837',
        'Digits' => 'hangup',
        'ToCountry' => 'US',
        'RecordingDuration' => '6',
        'CallerCity' => 'CITY',
        'ApiVersion' => '2010-04-01',
        'Caller' => '+17346825309',
        'CalledCity' => '',
        'RecordingSid' => 'Long hex',
    ];

    public function setUp()
    {
        parent::setUp();

        App::instance(
            TwilioClient::class,
            M::mock(TwilioClient::class)->shouldIgnoreMissing()
        );
    }

    public function checkTwiml()
    {
        $this->assertResponseOk();
        $this->assertNotFalse(strpos($this->response->getContent(), '<?xml'));
        $this->assertNotFalse(strpos($this->response->getContent(), '<Response>'));
    }

    public function test_call_is_twiml()
    {
        $this->post(route('hook.call'), $this->callPost);
    }

    public function test_call_succeeds()
    {
        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->callPost['Caller']),
        ]);
        $number->is_verified = true;
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.call'), $this->callPost);
        $this->checkTwiml();

        $this->assertNotFalse(strpos($this->response->getContent(), 'Your audio is now being recorded'));
    }

    public function test_after_call_is_twiml()
    {
        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost);
        $this->checkTwiml();
    }

    public function test_after_call_saves_recording()
    {
        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost);
        $this->checkTwiml();

        $this->assertEquals(1, $user->recordings->count());
        $recording = $user->recordings->first();

        $this->assertEquals($this->afterCallPost['Caller'], $recording->from);
        $this->assertEquals($this->afterCallPost['CallerCity'], $recording->city);
        $this->assertEquals($this->afterCallPost['CallerState'], $recording->state);
        $this->assertEquals($this->afterCallPost['RecordingUrl'], $recording->url);
        $this->assertEquals($this->afterCallPost['RecordingSid'], $recording->recording_sid);
        $this->assertEquals($this->afterCallPost['RecordingDuration'], $recording->duration);
    }

    public function test_after_call_notifies_owner()
    {
        $twilio = M::spy(TwilioClient::class);
        App::instance(TwilioClient::class, $twilio);

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost);

        $twilio->shouldHaveReceived('text')->once()->with(
            $number->number,
            M::on(function ($message) {
                return strpos($message, $this->afterCallPost['RecordingUrl']) !== false;
            })
        );
    }

    public function test_after_call_notifies_friends()
    {
        $twilio = M::spy(TwilioClient::class);
        App::instance(TwilioClient::class, $twilio);

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $friend1 = factory(Friend::class, 'verified')->make();
        $friend2 = factory(Friend::class, 'verified')->make();
        $friend3 = factory(Friend::class, 'verified')->make();

        $user->friends()->saveMany([$friend1, $friend2, $friend3]);

        $someoneElse = factory(User::class)->create();
        $someoneElsesFriend = factory(Friend::class)->make();
        $someoneElse->friends()->save($someoneElsesFriend);

        $this->post(route('hook.after-call'), $this->afterCallPost);

        // Awkward, must include text to the owner...
        $twilio->shouldHaveReceived('text')->times(4)->with(
            M::anyOf($number->number, $friend1->number, $friend2->number, $friend3->number),
            M::on(function ($message) {
                return strpos($message, $this->afterCallPost['RecordingUrl']) !== false;
            })
        );
    }

    public function test_it_is_listed_on_the_dashboard_after_being_added()
    {
        $user = factory(User::class)->create();
        $recording = factory(Recording::class)->make();
        $user->recordings()->save($recording);

        $this->be($user);

        $this
            ->get(route('dashboard'))
            ->see($recording->url)
            ->see($recording->city)
            ->see($recording->state)
            ->see($recording->duration);
    }

    public function test_it_increments_count_on_incoming_call()
    {
        $limiter = M::spy(\Illuminate\Cache\RateLimiter::class);
        app()->instance(\Illuminate\Cache\RateLimiter::class, $limiter);

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.call'), $this->callPost);
        $this->checkTwiml();

        $limiter->shouldHaveReceived('hit')->once()->withArgs([
            'twilio.'.$this->callPost['From'].'.calls',
            M::any()
        ]);
    }

    public function test_it_rejects_call_if_over_rate_limit()
    {
        $limiter = M::spy(\Illuminate\Cache\RateLimiter::class);
        app()->instance(\Illuminate\Cache\RateLimiter::class, $limiter);

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $limiter->shouldReceive('tooManyAttempts')->andReturn('true');

        $this->post(route('hook.call'), $this->callPost);
        $this->assertResponseStatus(429);
    }
}
