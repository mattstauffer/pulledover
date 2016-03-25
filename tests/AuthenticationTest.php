<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_cannot_login_if_not_registered()
    {
        $this
            ->visit(route('auth.login'))
            ->type('notreal@user.com', 'email')
            ->type('notrealpassword', 'password')
            ->press('Login');

        $this->see('Whoops!')
            ->dontSee('Logout');
    }

    public function test_user_cannot_register_without_accepting_disclaimer()
    {
        $this
            ->visit(route('auth.register'))
            ->type('Bob', 'name')
            ->type('email@email.com', 'email')
            ->type('schmassword', 'password')
            ->press('Register');

        $this->see('agreed with the disclaimer');
    }

    public function test_multiple_users_cannot_register_with_the_same_email()
    {
        $user = factory(User::class)->create();
        $user->email = 'email@email.com';
        $user->save();

        $this
            ->visit(route('auth.register'))
            ->type('Bob', 'name')
            ->type('email@email.com', 'email')
            ->type('schmassword', 'password')
            ->check('disclaimer')
            ->press('Register');

        $this->see('The email has already been taken');
    }

    public function test_non_admin_users_cannot_view_admin_pages()
    {
        $user = factory(User::class)->create();
        $user->role = 0;
        $user->save();
        $this->be($user);

        $this->get(route('admin.index'));
        $this->assertResponseStatus(302);
    }

    public function test_it_dispatches_verify_phone_number_job_on_register()
    {
        $this->expectsJobs(App\Jobs\VerifyPhoneNumber::class);
        $this->withoutPhoneValidation();
        $this
            ->visit(route('auth.register'))
            ->type('Bob', 'name')
            ->type('email@email.com', 'email')
            ->type('schmassword', 'password')
            ->type('5005550006', 'phone_number')
            ->check('disclaimer')
            ->press('Register');
    }
}
