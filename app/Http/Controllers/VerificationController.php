<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function own($hash)
    {
        $phoneNumber = PhoneNumber::where([
            'verification_hash' => $hash
        ])->firstOrFail();

        $phoneNumber->markVerified();

        Log::info('Phone number verified: ' . print_r($phoneNumber, true));
        return "Verified!";
    }

    public function friend($hash)
    {
        $friend = Friend::where([
            'verification_hash' => $hash
        ])->firstOrFail();

        $friend->markVerified();

        Log::info('Friend number verified: ' . print_r($friend, true));
        return "Verified!";
    }
}
