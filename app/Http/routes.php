<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('call', function () {
    // When someone calls our number, say hello and play 5 cowbells
    $response = new Services_Twilio_Twiml();
    $response->say('Hello');
    $response->play('https://api.twilio.com/cowbell.mp3', ["loop" => 5]);
    print $response;
});

Route::get('text-my-friend', function () {
    // test, of course this will happen in response to call
    $sid = env('TWILIO_ACCOUNT_SID');
    $token = env('TWILIO_ACCESS_TOKEN');

    $client = new Services_Twilio($sid, $token);
    $message = $client->account->messages->sendMessage(
        env('TWILIO_FROM_NUMBER'), // From a valid Twilio number
        '1234567890', // Text this number
        "Testing I am being pulled over!!"
    );

    print $message->sid;
});
