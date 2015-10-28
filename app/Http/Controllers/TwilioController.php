<?php

namespace App\Http\Controllers;

use App\Events\CallRecordingWasCompleted;
use App\Events\CallWasReceived;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class TwilioController extends Controller
{
    public function callHook(Request $request)
    {
        // @todo: Check if this is one of our stored numbers.
        // If not, punt to "you need to register first" flow
        $incomingNumber = $request->get("From");

        $response = new Services_Twilio_Twiml();
        $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
        $response->record([
            'maxLength' => 15,
            'action' => '/after-call',
        ]);

        event(new CallWasReceived($request->all()));

        print $response;
    }

    public function afterCallHook(Request $request, Services_Twilio $twilio)
    {
        // @todo: Save it to recordings table!

        // Grab recording and text it to someone
        // @todo: Text it to them and all their friends along with their pre-stored message?
        $message = $twilio->account->messages->sendMessage(
            env('TWILIO_FROM_NUMBER'), // From a valid Twilio number
            // env('ADMIN_PHONE_NUMBER'), // Text this number
            $request->get("From"),
            sprintf(
                "Number: %s\nFrom: %s %s\nURL: %s\n",
                $request->get("From"),
                $request->get("CallerCity"),
                $request->get("CallerState"),
                $request->get("RecordingUrl")
            )
        );

        $response = new Services_Twilio_Twiml();
        $response->say('Your fifteen seconds of fame are over. Goodbye!');
        $response->hangup();

        event(new CallRecordingWasCompleted($request->all()));

        print $response;
    }
}
