<?php

use App\Phone\NumberVerifier;
use App\Phone\TwilioClient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class NumberVerifierTest extends TestCase
{
    public function test_it_verifies_users_own_number()
    {
        $phoneNumber = '7346875309';
        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::mock(TwilioClient::class);

        // @todo: Is it possible to test the message too?
        $twilio->shouldReceive('text')->once()/*->with(
            $phoneNumber,
            $message // @todo: How do we build this message without duplicating the code?
        )*/;

        $verifier = new NumberVerifier($twilio);

        $verifier->verifyOwnNumber($phoneNumber, $key);
    }
}
