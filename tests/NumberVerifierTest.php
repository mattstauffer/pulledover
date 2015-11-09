<?php

use App\PhoneNumber;
use App\Phone\NumberVerifier;
use App\Phone\TwilioClient;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class NumberVerifierTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_verifies_users_own_number()
    {
        $user = factory(User::class)->create();
        $phoneNumber = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($phoneNumber);

        $phoneNumber = $phoneNumber->fresh();

        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::mock(TwilioClient::class);

        // @todo: Is it possible to test the message too?
        $twilio->shouldReceive('text')->once()/*->with(
            $phoneNumber,
            'How do I test this without manually copying the text message here?'
        )*/;

        $verifier = new NumberVerifier($twilio);

        $verifier->verifyOwnNumber($phoneNumber, $key);
    }

    public function test_it_marks_number_verified_after_url_visited()
    {
        $user = factory(User::class)->create();
        $phoneNumber = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($phoneNumber);

        $phoneNumber = $phoneNumber->fresh();

        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::mock(TwilioClient::class);

        // @todo: Is it possible to test the message too?
        $twilio->shouldReceive('text')->once()/*->with(
            $phoneNumber,
            'How do I test this without manually copying the text message here?'
        )*/;

        $verifier = new NumberVerifier($twilio);

        $verifier->verifyOwnNumber($phoneNumber, $key);

        $phoneNumber = $phoneNumber->fresh();
        $this->assertFalse($phoneNumber->is_verified);

        $this->visit($verifier->ownNumberVerificationUrl($key));

        $phoneNumber = $phoneNumber->fresh();
        $this->assertTrue($phoneNumber->is_verified);
    }
}
