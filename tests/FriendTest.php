<?php

use App\Friend;
use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class FriendTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_cannot_add_the_same_friend_twice()
    {
        Validator::extend('valid_phone', function ($attribute, $value, $parameters, $validator) {
            // Skip validation because we can't validate a phone number on test creds
            return true;
        });

        $user = factory(User::class)->create();
        $phoneNumber = factory(PhoneNumber::class, 'verified')->make();
        $user->phoneNumbers()->save($phoneNumber);
        $this->be($user);

        $number = '7345678309';

        $this->post(route('friends.store'), ['name' => 'Sally', 'number' => $number]);
        $this->post(route('friends.store'), ['name' => 'Schmally', 'number' => $number]);

        $friends = $user->friends()->where(['number' => $number])->get();

        $this->assertEquals(1, $friends->count());
    }

    public function test_it_is_listed_on_the_dashboard_after_being_added()
    {
        $user = factory(User::class)->create();
        $friend = factory(Friend::class)->make();
        $user->friends()->save($friend);

        $this->be($user);

        $this
            ->get(route('dashboard'))
            ->see($friend->formattedNumber)
            ->see($friend->name);
    }

    public function test_it_strips_non_numeric_characters_on_store()
    {
        $user = factory(User::class)->create();
        $user->phoneNumbers()->save(factory(PhoneNumber::class, 'verified')->make());
        $this->be($user);

        //don't nobody wanna test twilio validators right now
        Validator::extend('valid_phone', function ($attribute, $value, $parameters, $validator) {
            // Skip validation because we can't validate a phone number on test creds
            return true;
        });

        $this->post(route('friends.store'), ['name' => 'Sally', 'number' => "(500) 555-5555"]);
        $this->seeInDatabase('friends', ['number' => '5005555555']);
    }

    public function test_it_redirects_if_user_number_is_not_verified()
    {
        //this just makes sure the verified numbers for other people aren't included in the count
        $notMe = factory(User::class)->create();
        $notMe->phoneNumbers()->save(factory(PhoneNumber::class, 'verified')->make());

        $this->be(factory(User::class)->create());
        $this->post(route('friends.store'), ['name' => 'Sally', 'number' => "(500) 555-5555"]);
        $this->assertRedirectedTo(route('dashboard'));
        $this->assertSessionHas('messages', ['You need to verify a phone number before you can add any friends.']);
    }
}
