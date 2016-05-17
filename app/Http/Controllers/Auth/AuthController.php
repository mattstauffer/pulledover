<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\VerifyPhoneNumber;
use App\User;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'phone_number' => 'required|digits:10|integer|globally_unique_number|valid_phone',
            'disclaimer' => 'required'
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        try {
            $number = $user->phoneNumbers()->create([
                'number' => $data['phone_number'],
            ]);

            $this->dispatch(new VerifyPhoneNumber($number));
        } catch (Exception $e) {
            \Log::error($e);
        }

        return $user;
    }
}
