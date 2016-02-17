<?php

namespace App\Http\Controllers;

use Exception;
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

        return view('verified');
    }

    public function friend($hash)
    {
        try {
            $friend = Friend::where([
                'verification_hash' => $hash
            ])->firstOrFail();
        } catch (Exception $e) {
            return "Sorry, but we can't find that verification code.";
        }

        $friend->markVerified();

        Log::info('Friend number verified: ' . print_r($friend, true));

        return view('verified');
    }
}
