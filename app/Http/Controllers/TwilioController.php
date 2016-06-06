<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwilioRequest as Request;
use App\Jobs\NotifyFriendsOfRecording;
use App\Jobs\NotifyOwnerOfRecording;
use App\PhoneNumber;
use App\Recording;
use Exception;
use Services_Twilio_Twiml as TwimlGenerator;

class TwilioController extends Controller
{

    public function callHook(Request $request)
    {
        try {
            $phoneNumber = $request->phoneNumber();
        } catch (Exception $e) {
            return $this->promptToRegister($request);
        }

        return $this->startRecording();
    }

    private function startRecording()
    {
        $response = new TwimlGenerator;
        $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
        $response->record([
            'maxLength' => 3600,
            'action' => '/after-call',
            'timeout' => 600,
        ]);

        return $response;
    }

    private function promptToRegister($request)
    {
        $response = new TwimlGenerator;
        $response->say('Sorry, but this is not a registered number. Please log into your account at Pulled Over Dot US, add a phone number, and verify it to register. Thank you!');
        $response->hangup();

        return $response;
    }

    public function afterCallHook(Request $request)
    {
        $recording = $this->saveRecording($request);
        $this->dispatch(new NotifyOwnerOfRecording($recording));
        $this->dispatch(new NotifyFriendsOfRecording($recording));

        return $this->hangup();
    }

    private function saveRecording($request)
    {
        $number = $request->phoneNumber();

        $recording = new Recording([
            'from' => $request->input('Caller'),
            'city' => $request->input('CallerCity'),
            'state' => $request->input('CallerState'),
            'url' => $request->input('RecordingUrl'),
            'recording_sid' => $request->input('RecordingSid'),
            'duration' => $request->input('RecordingDuration'),
            'json' => json_encode($request->all()),
        ]);

        $number->user->recordings()->save($recording);

        return $recording;
    }

    private function hangUp()
    {
        $response = new TwimlGenerator;
        $response->say('Sorry, but the recording will stop after an hour of recording or ten minutes of silence.');
        $response->hangup();

        return $response;
    }
}
