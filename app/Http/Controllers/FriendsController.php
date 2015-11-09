<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\VerifyPhoneNumberFriendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index()
    {
        return view('friends.index')
            ->with('friends', Auth::user()->friends);
    }

    public function create()
    {
        return view('friends.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'number' => 'required|digits:10|integer|unique_friend'
        ]);

        $number = Auth::user()->friends()->create([
            'number' => $request->get('number'),
            'name' => $request->get('name'),
        ]);

        $this->dispatch(new VerifyPhoneNumberFriendship($number));

        return redirect()->route('friends.index');
    }
}
