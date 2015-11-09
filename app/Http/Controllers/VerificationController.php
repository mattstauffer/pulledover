<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\PhoneNumber;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function own($hash)
    {
        $phoneNumber = PhoneNumber::where([
            'verification_hash' => $hash
        ])->firstOrFail();

        $phoneNumber->markVerified();

        return "Verified!";
    }

    public function friend($hash)
    {
        $friend = Friend::where([
            'verification_hash' => $hash
        ])->firstOrFail();

        $friend->markVerified();

        return "Verified!";
    }
}
