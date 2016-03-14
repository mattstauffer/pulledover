<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\VerifyPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function __construct()
    {
        $this->middleware('number.verified');
    }

    public function create()
    {
        return view('friends.create');
    }

    public function store(Request $request)
    {
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

        $this->dispatch(new VerifyPhoneNumber($number));

        return redirect()->route('dashboard');
    }
}
