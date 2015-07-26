<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('call', function () {
    // When someone calls our number, say hello and play 5 cowbells
    $response = new Services_Twilio_Twiml();
    $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
    $response->record([
        // 'maxLength' => 120,
        'action' => '/after-call',
    ]);
    print $response;
});

Route::get('table', function (Illuminate\Http\Request $request) {
    Log::info(print_r($request, true));
});

Route::post('after-call', function (Illuminate\Http\Request $request) {
    // Grab recording and text it to someone
    // @todo https://www.twilio.com/blog/2015/07/record-a-phone-call-in-ruby.html

    Log::info(print_r($request));

    $response = new Services_Twilio_Twiml();
    $response->say('If you are hearing this, our application has run out of space or something and is hanging up.');
    $response->hangup();

    print $response;

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
