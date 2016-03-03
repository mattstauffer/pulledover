<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\PhoneNumber::class, function (Faker\Generator $faker) {
    return [
        'number' => '734' . $faker->randomNumber(7),
        'is_verified' => false
    ];
});

$factory->defineAs(App\PhoneNumber::class, 'verified', function (Faker\Generator $faker) use ($factory) {
    $phoneNumber = $factory->raw(App\PhoneNumber::class);

    return array_merge($phoneNumber, ['is_verified' => true]);
});

$factory->define(App\Friend::class, function (Faker\Generator $faker) {
    return [
        'number' => '313' . $faker->randomNumber(7),
        'is_verified' => false,
        'name' => $faker->name
    ];
});

$factory->defineAs(App\Friend::class, 'verified', function (Faker\Generator $faker) use ($factory) {
    $friend = $factory->raw(App\Friend::class);

    return array_merge($friend, ['is_verified' => true]);
});

$factory->define(App\Recording::class, function (Faker\Generator $faker) {
    return [
        'from' => '+1313' . $faker->randomNumber(7),
        'city' => $faker->city,
        'state' => $faker->state,
        'url' => $faker->url,
        'recording_sid' => $faker->randomNumber(8) . $faker->randomNumber(8) . $faker->randomNumber(8) . $faker->randomNumber(8),
        'duration' => $faker->randomNumber(2),
        'json' => '{}',
    ];
});
