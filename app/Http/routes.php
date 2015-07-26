<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('call', function () {
    $response = new Services_Twilio_Twiml();
    $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
    $response->record([
        // 'maxLength' => 120,
        'action' => '/after-call',
    ]);

    // @todo: Trigger call received event, with call information. Or is that not possible? Hurgh. Do we get anything posted here?

    print $response;
});

Route::post('after-call', function (Illuminate\Http\Request $request) {
    // Grab recording and text it to someone
    // @todo https://www.twilio.com/blog/2015/07/record-a-phone-call-in-ruby.html

    // Testing
    $sid = env('TWILIO_ACCOUNT_SID');
    $token = env('TWILIO_ACCESS_TOKEN');

    $client = new Services_Twilio($sid, $token);
    $message = $client->account->messages->sendMessage(
        env('TWILIO_FROM_NUMBER'), // From a valid Twilio number
        env('ADMIN_PHONE_NUMBER'), // Text this number
        sprintf(
            "Number: %s\nFrom: %s %sURL: %s\n",
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

    // https://github.com/thatpodcast/voicemail/blob/master/app.php
    // $email->addTo($to)
    //     ->setFrom($to)
    //     ->setSubject("New Voicemail!")
    //     ->setText(sprintf(
    //         "SID: %s\nDuration: %s\nURL: %s\n",
    //         $request->get("CallSid"),
    //         $request->get("RecordingDuration"),
    //         $request->get("RecordingUrl")
    //     ));
    // $rsp = $app['sendgrid']->send($email);
    // if (is_object($rsp) && isset($rsp->errors)) {
    //     $app['logger']->error(json_encode($rsp));
    // }
    // $twiml = new Twiml;
    // $twiml->say("Thanks for leaving a message, you're awesome.");
    // $twiml->hangup();
    // return new Response((string) $twiml);
});

// Route::get('text-my-friend', function () {
//     // test, of course this will happen in response to call
//     $sid = env('TWILIO_ACCOUNT_SID');
//     $token = env('TWILIO_ACCESS_TOKEN');

//     $client = new Services_Twilio($sid, $token);
//     $message = $client->account->messages->sendMessage(
//         env('TWILIO_FROM_NUMBER'), // From a valid Twilio number
//         '1234567890', // Text this number
//         "Testing I am being pulled over!!"
//     );

//     print $message->sid;
// });
