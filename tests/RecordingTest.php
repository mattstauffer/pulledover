<?php

use App\PhoneNumber;
use App\Phone\TwilioClient;
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
        'Caller' => '+10987654321',
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

    public function test_call_is_twiml()
    {
        $this->post(route('hook.call'), $this->callPost);
        $this->assertResponseOk();
        $this->assertNotFalse(strpos($this->response->getContent(), '<?xml'));
        $this->assertNotFalse(strpos($this->response->getContent(), '<Response>'));
    }

    public function test_after_call_is_twiml()
    {
        App::instance(
            TwilioClient::class,
            M::mock(TwilioClient::class)->shouldIgnoreMissing()
        );

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost);
        $this->assertResponseOk();
        $this->assertNotFalse(strpos($this->response->getContent(), '<?xml'));
        $this->assertNotFalse(strpos($this->response->getContent(), '<Response>'));
    }

    public function test_after_call_saves_recording()
    {
        App::instance(
            TwilioClient::class,
            M::mock(TwilioClient::class)->shouldIgnoreMissing()
        );

        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost);
        $this->assertResponseOk();
        $this->assertNotFalse(strpos($this->response->getContent(), '<?xml'));
        $this->assertNotFalse(strpos($this->response->getContent(), '<Response>'));

        $this->assertEquals(1, $user->recordings->count());
        $recording = $user->recordings->first();

        // $this->assertEquals($this->afterCallPost['Caller'], );
        // @todo: Check its values against POST
    }
}
