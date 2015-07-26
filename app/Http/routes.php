<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('call', function () {
    $response = new Services_Twilio_Twiml();
    $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
    $response->record([
        'maxLength' => 15,
        'action' => '/after-call',
    ]);

    // @todo: Trigger call received event, with call information. Or is that not possible? Hurgh. Do we get anything posted here?

    print $response;
});

Route::post('after-call', function (Illuminate\Http\Request $request) {
    // Grab recording and text it to someone
    $sid = env('TWILIO_ACCOUNT_SID');
    $token = env('TWILIO_ACCESS_TOKEN');

    $client = new Services_Twilio($sid, $token);
    $message = $client->account->messages->sendMessage(
        env('TWILIO_FROM_NUMBER'), // From a valid Twilio number
        env('ADMIN_PHONE_NUMBER'), // Text this number
        sprintf(
            "Number: %s\nFrom: %s %s\nURL: %s\n",
            $request->get("From"),
            $request->get("CallerCity"),
            $request->get("CallerState"),
            $request->get("RecordingUrl")
        )
    );

    $response = new Services_Twilio_Twiml();
    $response->say('If you are hearing this, our application has run out of space or something and is hanging up.');
    $response->hangup();

    // @todo: Emit voicemail received command or whatever

    print $response;

/**
 * [AccountSid] => Long hex thing
 * [ToZip] =>
 * [FromState] => FL
 * [Called] => +18443116837
 * [FromCountry] => US
 * [CallerCountry] => US
 * [CalledZip] =>
 * [Direction] => inbound
 * [FromCity] => CITY
 * [CalledCountry] => US
 * [CallerState] => FL
 * [CallSid] => Long hex thing
 * [CalledState] =>
 * [From] => +10987654321
 * [CallerZip] => 12345
 * [FromZip] => 12345
 * [CallStatus] => completed
 * [ToCity] =>
 * [ToState] =>
 * [RecordingUrl] => http://api.twilio.com/2010-04-01/Accounts/Long hex/Recordings/Long hex
 * [To] => +18443116837
 * [Digits] => hangup
 * [ToCountry] => US
 * [RecordingDuration] => 6
 * [CallerCity] => CITY
 * [ApiVersion] => 2010-04-01
 * [Caller] => +10987654321
 * [CalledCity] =>
 * [RecordingSid] => Long hex
 */
});
