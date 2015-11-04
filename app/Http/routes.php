<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'Auth'], function () {
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

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'AccountController@index');
    Route::resource('numbers', 'NumbersController');
    Route::resource('friends', 'FriendsController');
    Route::get('recordings', 'RecordingsController@index');
});

Route::post('call', 'TwilioController@callHook');
Route::post('after-call', 'TwilioController@afterCallHook');

Route::get('verify/own/{hash}', ['as' => 'phones.verify', 'VerificationController@own']);
Route::get('verify/friend/{hash}', ['as' => 'friends.verify', 'VerificationController@friend']);
