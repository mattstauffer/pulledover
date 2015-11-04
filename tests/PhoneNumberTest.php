<?php

use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PhoneNumberTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_gets_phone_number_by_number()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($number);

        $foundNumber = PhoneNumber::findByNumber($number->number);

        $this->assertEquals($number->id, $foundNumber->id);
    }
}
