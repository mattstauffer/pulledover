<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'Auth'], function() {
    Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
    Route::post('register', ['uses' => 'AuthController@postRegister']);
    Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
    Route::post('login', ['uses' => 'AuthController@postLogin']);
    Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    Route::get('password/email', ['as' => 'password.email', 'uses' => 'PasswordController@getEmail']);
    Route::post('password/email', ['uses' => 'PasswordController@postEmail']);
    Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'PasswordController@getReset']);
    Route::post('password/reset', ['uses' => 'PasswordController@postReset']);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('home', 'AccountController@index');
    Route::get('numbers', 'NumbersController@index');
    Route::get('friends', 'FriendsController@index');
    Route::get('recordings', 'RecordingsController@index');
});

Route::post('call', function (Illuminate\Http\Request $request) {
    // @todo: Check if this is one of our stored numbers. If not, punt to "you need to register first" flow

    $response = new Services_Twilio_Twiml();
    $response->say('Thank you for calling Pulled Over. Your audio is now being recorded.');
    $response->record([
        'maxLength' => 15,
        'action' => '/after-call',
    ]);

    event(new App\Events\CallWasReceived($request->all()));

    print $response;
});

Route::post('after-call', function (Illuminate\Http\Request $request, Services_Twilio $twilio) {
    // Grab recording and text it to someone
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

    event(new App\Events\CallRecordingWasCompleted($request->all()));

    print $response;
});
