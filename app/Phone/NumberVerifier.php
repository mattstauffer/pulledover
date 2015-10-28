<?php

namespace App\Phone;

use App\Phone\TwilioClient;

class NumberVerifier
{
    private $twilio;

    public function __construct(TwilioClient $twilio)
    {
        $this->twilio = $twilio;
    }

    public function verifyOwnNumber($number, $key)
    {
        return $this->twilio->text(
            $number,
            'If you requested this validation from Pulled Over, please visit ' . url(route('phones.verify', ['key' => $key]))
        );
    }

    public function verifyFriendsNumber($number, $key, $name)
    {
        return $this->twilio->text(
            $number,
            'Your friend ' + $name + ' wants to add you as a friend on Pulled Over. If you want that too, please visit ' . url(route('friends.verify', ['key' => $key]))
        );
    }
}
