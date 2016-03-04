<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('donate', ['as' => 'donate', function () {
        return view('donate');
    }]);

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
        Route::get('home', ['as' => 'dashboard', 'uses' => 'AccountController@index']);
        Route::resource('numbers', 'NumbersController', ['only' => ['create', 'store']]);
        Route::resource('friends', 'FriendsController', ['only' => ['create', 'store']]);
        Route::get('recordings', 'RecordingsController@index');

        Route::get('dismiss-welcome', ['as' => 'dismiss-welcome', 'uses' => 'AccountController@dismissWelcome']);

        Route::get('admin', ['as' => 'admin.index', 'uses' => 'AdminController@index']);
    });

    Route::post('call', ['as' => 'hook.call', 'uses' => 'TwilioController@callHook']);
    Route::post('after-call', ['as' => 'hook.after-call', 'uses' => 'TwilioController@afterCallHook']);

    Route::get('verify/own/{hash}', ['as' => 'phones.verify', 'uses' => 'VerificationController@own']);
    Route::get('verify/friend/{hash}', ['as' => 'friends.verify', 'uses' => 'VerificationController@friend']);
});
