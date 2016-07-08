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

class TwilioSignatureTest extends TestCase
{
    use DatabaseMigrations;

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

    public function test_only_twilio_can_post_to_url()
    {
        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost, $this->twilioHeader());
        $this->assertResponseStatus(200);
    }

    public function test_twili_mismatch_signature()
    {
        $user = factory(User::class)->create();
        $number = new PhoneNumber([
            'number' => TwilioClient::formatNumberFromTwilio($this->afterCallPost['Caller']),
        ]);
        $user->phoneNumbers()->save($number);

        $this->post(route('hook.after-call'), $this->afterCallPost, [
            'X-Twilio-Signature' => 'fakeHash'
        ]);
        $this->assertResponseStatus(401);
    }

    protected function twilioHeader()
    {
        return [
            'X-Twilio-Signature' => app(Services_Twilio_RequestValidator::class)->computeSignature(
                route('hook.after-call'), $this->afterCallPost
            )
        ];
    }
}
