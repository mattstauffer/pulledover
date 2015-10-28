<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NumberVerifierTest extends TestCase
{
    public function test_it_verifies_users_own_number()
    {
        $this->markTestIncomplete('TODO');

        // @todo: Make a Twilio wrapper so we're not mocking something we don't own?
        $twilio = M::mock('Services_Twilio');
        $verifier = new NumberVerifier($twilio);

        $phoneNumber = '7346875309';
        $key = '12531234hio123hipgqwerqwe';

        $verifier->verifyOwnNumber($phoneNumber, $key);

        $twilio->shouldReceive('textOrSomething', ['idunnoletsmake this actually work plz']);
    }
}
