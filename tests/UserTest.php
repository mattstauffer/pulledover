<?php

use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_gets_user_by_phone_number()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($number);

        $foundUser = User::findByPhoneNumber($number->number);

        $this->assertEquals($user->id, $foundUser->id);
    }
}
