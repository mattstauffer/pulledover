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
}
