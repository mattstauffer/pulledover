<?php

namespace App\Phone;

class NumberVerifier
{
    private $twilio;

    public function __construct($twilio)
    {
        $this->twilio = $twilio;
    }

    public function verifyOwnNumber($number, $key)
    {
        return $this->text(
            $number,
            'If you requested this validation from Pulled Over, please visit ' . site_url(route('phones.verify', ['key' => $key]))
        );
    }

    public function verifyFriendsNumber($number, $key, $name)
    {
        return $this->text(
            $number,
            'Your friend ' + $name + ' wants to add you as a friend on Pulled Over. If you want that too, please visit ' . site_url(route('friends.verify', ['key' => $key]))
        );
    }

    private function text($number, $message)
    {
        dd('twilio send this message');
    }
}
