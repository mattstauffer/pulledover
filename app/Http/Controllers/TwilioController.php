<?php

namespace App\Http\Controllers;

use App\Events\CallRecordingWasCompleted;
use App\Events\CallWasReceived;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\PhoneNumber;
use App\Phone\TwilioClient;
use App\Recording;
use Exception;
use Illuminate\Http\Request;
use Services_Twilio_Twiml as TwimlGenerator;

class TwilioController extends Controller
{
    public function callHook(Request $request)
    {
        try {
            $phoneNumber = PhoneNumber::findByNumber($request->input("From"));
        } catch (Exception $e) {
            return $this->promptToRegister();
        }

        $response = new TwimlGenerator;
        $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
        $response->record([
            'maxLength' => 15,
            'action' => '/after-call',
        ]);

        event(new CallWasReceived($phoneNumber, $request->all()));

        return $response;
    }

    private function promptToRegister()
    {
        $response = new TwimlGenerator;
        $response->say('This is not a registered number. Please do blah blah blah to register.');
        $response->hangup();

        return $response;
    }

    public function afterCallHook(Request $request, TwilioClient $twilio)
    {
        // Grab recording and text it to someone
        // @todo: Text it to them and all their friends along with their pre-stored message?
        $twilio->text(
            TwilioClient::formatNumberFromTwilio($request->get("From")),
            sprintf(
                "Number: %s\nFrom: %s %s\nURL: %s\n",
                $request->get("From"),
                $request->get("CallerCity"),
                $request->get("CallerState"),
                $request->get("RecordingUrl")
            )
        );

        $this->saveRecording($request);

        $response = new TwimlGenerator;
        $response->say('Your fifteen seconds of fame are over. Goodbye!');
        $response->hangup();

        event(new CallRecordingWasCompleted($request->all()));

        return $response;
    }

    private function saveRecording($request)
    {
        $recording = Recording::create([
            'from' => $request->input('caller'),
            // @todo: Is caller city different from from city?
            'city' => $request->input('CallerCity'),
            'state' => $request->input('CallerState'),
            'url' => $request->input('RecordingUrl'),
            'recording_sid' => $request->input('RecordingSid'),
            'duration' => $request->input('RecordingDuration'),
            'json' => json_encode($request->all()),
        ]);
    }
}
