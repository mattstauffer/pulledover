<?php

use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

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

    public function test_user_cannot_add_the_same_number_twice()
    {
        $this->withoutPhoneValidation();

        $user = factory(User::class)->create();
        $this->be($user);

        $number = '7345678309';

        $this->post(route('numbers.store'), ['name' => 'Sally', 'number' => $number]);
        $this->post(route('numbers.store'), ['name' => 'Schmally', 'number' => $number]);

        $numbers = $user->phoneNumbers()->where(['number' => $number])->get();

        $this->assertEquals(1, $numbers->count());
    }

    public function test_it_formats_numbers_correctly()
    {
        $phoneNumber = factory(PhoneNumber::class)->make();
        $phoneNumber->number = '8582930491';
        $this->assertEquals('(858) 293-0491', $phoneNumber->formattedNumber);
    }

    public function test_it_is_listed_on_the_dashboard_after_being_added()
    {
        $user = factory(User::class)->create();
        $number = factory(PhoneNumber::class)->make();
        $user->phoneNumbers()->save($number);

        $this->be($user);

        $this
            ->get(route('dashboard'))
            ->see($number->formattedNumber);
    }

    public function test_it_shows_user_error_for_twilio_rejected_phone_number()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this
            ->visit(route('numbers.create'))
            ->type('1000000001', 'number')
            ->press('Add New Number');

        $this->see('Whoops!');
    }

    public function test_it_strips_non_numeric_characters_on_store()
    {
        $this->withoutMiddleware()->withoutPhoneValidation();

        $user = factory(User::class)->create();
        $this->be($user);
        $this->post(route('numbers.store'), ['number' => '(500) 555-0000']);
        $this->seeInDatabase('phone_numbers', ['number' => '5005550000']);
    }

    public function test_it_cannot_add_blacklisted_numbers()
    {
        $this->withoutMiddleware()->withoutPhoneValidation();
        $user = factory(User::class)->create();
        $this->be($user);
        $this->post(route('numbers.store'), ['number' => '5005550004']);

        $this->assertEquals([trans('twilio.21610')], \Session::get('errors')->all());

        $this->dontSeeInDatabase('phone_numbers', ['number' => '5005550004']);
    }
}
