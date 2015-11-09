<?php

use App\PhoneNumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class NumberTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_cannot_add_the_same_number_twice()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $number = '7345678309';

        $this->post('/numbers', ['name' => 'Sally', 'number' => $number]);
        $this->post('/numbers', ['name' => 'Schmally', 'number' => $number]);

        $numbers = $user->phoneNumbers()->where(['number' => $number])->get();

        $this->assertEquals(1, $numbers->count());
    }
}
