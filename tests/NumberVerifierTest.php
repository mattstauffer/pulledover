<?php

use App\Friend;
use App\PhoneNumber;
use App\Phone\NumberVerifier;
use App\Phone\TwilioClient;
use App\User;
use Illuminate\Contracts\Routing\UrlGenerator;
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

        $twilio = M::spy(TwilioClient::class);
        $urlGenerator = app(UrlGenerator::class);

        $verifier = new NumberVerifier($twilio, $urlGenerator);

        $verifier->verifyOwnNumber($phoneNumber, $key);

        $url = route('phones.verify', ['key' => $key]);

        $twilio->shouldHaveReceived('text')->once()->with(
            $phoneNumber->number,
            M::on(function ($message) use ($url) {
                return strpos($message, $url) !== false;
            })
        );
    }

    public function test_it_marks_users_own_number_verified_after_url_visited()
    {
        $user = factory(User::class)->create();
        $phoneNumber = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($phoneNumber);

        $phoneNumber = $phoneNumber->fresh();

        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::spy(TwilioClient::class);

        $urlGenerator = app(UrlGenerator::class);

        $verifier = new NumberVerifier($twilio, $urlGenerator);

        $verifier->verifyOwnNumber($phoneNumber, $key);

        $phoneNumber = $phoneNumber->fresh();
        $this->assertFalse($phoneNumber->is_verified);

        $url = route('phones.verify', ['key' => $key]);
        $this->visit($url);

        $phoneNumber = $phoneNumber->fresh();
        $this->assertTrue($phoneNumber->is_verified);

        $twilio->shouldHaveReceived('text')->once()->with(
            $phoneNumber->number,
            M::on(function ($message) use ($url) {
                return strpos($message, $url) !== false;
            })
        );
    }

    public function test_it_verifies_friends_number()
    {
        $user = factory(User::class)->create();
        $friend = factory(Friend::class)->make();
        $user->friends()->save($friend);

        $friend = $friend->fresh();

        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::spy(TwilioClient::class);
        $urlGenerator = app(UrlGenerator::class);

        $verifier = new NumberVerifier($twilio, $urlGenerator);

        $verifier->verifyFriendsNumber($friend, $key);

        $url = route('friends.verify', ['key' => $key]);

        $twilio->shouldHaveReceived('text')->once()->with(
            $friend->number,
            M::on(function ($message) use ($url) {
                return strpos($message, $url) !== false;
            })
        );
    }

    public function test_it_marks_friends_number_verified_after_url_visited()
    {
        $user = factory(User::class)->create();
        $friend = factory(Friend::class)->make();
        $user->friends()->save($friend);

        $friend = $friend->fresh();

        $key = '12531234hio123hipgqwerqwe';

        $twilio = M::spy(TwilioClient::class);
        $urlGenerator = app(UrlGenerator::class);

        $verifier = new NumberVerifier($twilio, $urlGenerator);

        $verifier->verifyFriendsNumber($friend, $key);

        $friend = $friend->fresh();
        $this->assertFalse($friend->is_verified);

        $url = route('friends.verify', ['key' => $key]);
        $this->visit($url);

        $friend = $friend->fresh();
        $this->assertTrue($friend->is_verified);

        $twilio->shouldHaveReceived('text')->once()->with(
            $friend->number,
            M::on(function ($message) use ($url) {
                return strpos($message, $url) !== false;
            })
        );
    }
}
