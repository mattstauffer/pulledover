<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return view('dashboard')
            ->with('numbers', Auth::user()->phoneNumbers)
            ->with('friends', Auth::user()->friends)
            ->with('recordings', Auth::user()->recordings);
    }

    public function dismissWelcome()
    {
        Auth::user()->dismissed_welcome = true;
        Auth::user()->save();

        return response()->json([
            'success' => true,
            'message' => 'Welcome successfully marked as dismissed.'
        ]);
    }
}
