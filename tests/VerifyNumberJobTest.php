<?php

use App\Phone\NumberVerifier;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as M;

class VerifyNumberJobTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_call_correct_verifier_based_on_receiver_class()
    {
        $user = factory(\App\User::class)->create();
        $number = factory(\App\PhoneNumber::class)->make();
        $friend = factory(\App\Friend::class)->make();

        $user->phoneNumbers()->save($number);
        $user->friends()->save($friend);

        $verifier = M::mock(NumberVerifier::class);
        $verifier->shouldReceive('verifyFriendsNumber')->once()->with($friend, M::any());
        $verifier->shouldReceive('verifyOwnNumber')->once()->with($number, M::any());

        $job = new \App\Jobs\VerifyPhoneNumber($friend);
        $job->handle($verifier);

        $job = new \App\Jobs\VerifyPhoneNumber($number);
        $job->handle($verifier);
    }

    /**
     * @expectedException \Exception
     */
    public function test_it_throws_exception_if_receiver_type_not_implemented()
    {
        $job = new \App\Jobs\VerifyPhoneNumber(M::mock());
        $job->handle(M::mock(NumberVerifier::class));
    }
}
