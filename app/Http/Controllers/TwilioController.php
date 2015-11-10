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
    private $twilio;

    public function __construct(TwilioClient $twilio)
    {
        $this->twilio = $twilio;
    }

    public function callHook(Request $request)
    {
        try {
            $phoneNumber = PhoneNumber::verified()->findByTwilioNumber($request->input("From"));
        } catch (Exception $e) {
            return $this->promptToRegister();
        }

        $response = new TwimlGenerator;
        $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
        $response->record([
            'maxLength' => 3600,
            'action' => '/after-call',
        ]);

        event(new CallWasReceived($phoneNumber, $request->all()));

        return $response;
    }

    private function promptToRegister()
    {
        $response = new TwimlGenerator;
        $response->say('Sorry, but this is not a registered number. Please log into your account at Pulled Over Dot US, add a phone number, and verify it to register.');
        $response->hangup();

        return $response;
    }

    public function afterCallHook(Request $request)
    {
        $this->saveRecording($request);
        $this->notifyOwnerOfRecording($request);
        $this->notifyFriendsOfRecording($request);

        $response = new TwimlGenerator;
        $response->say('Sorry, but we can\'t record more than an hour.');
        $response->hangup();

        event(new CallRecordingWasCompleted($request->all()));

        return $response;
    }

    // Move into the event, or drop the event
    private function notifyOwnerOfRecording($request)
    {
        $this->twilio->text(
            TwilioClient::formatNumberFromTwilio($request->get("From")),
            sprintf(
                "New Pulledover.us recording. Number: %s\nFrom: %s %s\nURL: %s\n",
                $request->get("From"),
                $request->get("CallerCity"),
                $request->get("CallerState"),
                $request->get("RecordingUrl")
            )
        );
    }

    // Move into the event, or drop the event
    private function notifyFriendsofRecording($request)
    {
        $user = PhoneNumber::findByTwilioNumber($request->get('From'))->user;

        $user->friends->each(function ($friend) use ($user, $request) {
            $this->twilio->text(
                $friend->number,
                sprintf(
                    "Your friend {$user->name} has completed a PulledOver recording. From %s, City %s, State %s, Recording %s",
                    $request->get("From"),
                    $request->get("CallerCity"),
                    $request->get("CallerState"),
                    $request->get("RecordingUrl")
                )
            );
        });
    }

    // Job?
    private function saveRecording($request)
    {
        $number = PhoneNumber::findByTwilioNumber($request->input('Caller'));
        $user = $number->user;

        $recording = new Recording([
            'from' => $request->input('Caller'),
            'city' => $request->input('CallerCity'),
            'state' => $request->input('CallerState'),
            'url' => $request->input('RecordingUrl'),
            'recording_sid' => $request->input('RecordingSid'),
            'duration' => $request->input('RecordingDuration'),
            'json' => json_encode($request->all()),
        ]);

        $user->recordings()->save($recording);
    }
}
