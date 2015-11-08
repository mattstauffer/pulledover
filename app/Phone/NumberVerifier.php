<?php

namespace App\Phone;

use App\Friend;
use App\PhoneNumber;
use App\Phone\TwilioClient;

class NumberVerifier
{
    private $twilio;

    public function __construct(TwilioClient $twilio)
    {
        $this->twilio = $twilio;
    }

    public function verifyOwnNumber(PhoneNumber $number, $key)
    {
        $number->verification_hash = $key;
        $number->save();

        return $this->twilio->text(
            $number->number,
            'If you requested this validation from Pulled Over, please visit ' . url(route('phones.verify', ['key' => $key]))
        );
    }

    // @todo: Write methods to handle incoming verificaiton

    public function verifyFriendsNumber(Friend $number, $key, $name)
    {
        $number->verification_hash = $key;
        $number->save();

        return $this->twilio->text(
            $number->number,
            'Your friend ' + $name + ' wants to add you as a friend on Pulled Over. If you want that too, please visit ' . url(route('friends.verify', ['key' => $key]))
        );
    }
}
