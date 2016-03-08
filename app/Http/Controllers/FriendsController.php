<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\VerifyPhoneNumberFriendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    private $quit = false;

    public function __construct()
    {
        if (Auth::user()->phoneNumbers()->verified()->count() == 0) {
            $this->quit = true;
        }
    }

    private function quit()
    {
        // This should be a middleware
        return redirect()
            ->route('dashboard')
            ->with('messages', ['You need to verify a phone number before you can add any friends.']);
    }

    public function create()
    {
        if ($this->quit) {
            return $this->quit();
        }

        return view('friends.create');
    }

    public function store(Request $request)
    {
        if ($this->quit) {
            return $this->quit();
        }

        $number = preg_replace('/[^\d]/', '', $request->input('number'));

        $validator = $this->getValidationFactory()->make(compact('number'), [
            'number' => 'required|digits:10|integer|unique_friend|valid_phone'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $number = Auth::user()->friends()->create([
            'number' => $number,
            'name' => $request->get('name'),
        ]);

        $this->dispatch(new VerifyPhoneNumberFriendship($number));

        return redirect()->route('dashboard');
    }
}
