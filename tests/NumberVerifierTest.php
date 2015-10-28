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
        $twilio = M::mock(TwilioClient::class);
        $verifier = new NumberVerifier($twilio);

        $phoneNumber = '7346875309';
        $key = '12531234hio123hipgqwerqwe';

        $verifier->verifyOwnNumber($phoneNumber, $key);

        $twilio->shouldReceive('text')->with(
            env('TWILIO_FROM_NUMBER'),
            $phoneNumber,
            $message
        );
    }
}
