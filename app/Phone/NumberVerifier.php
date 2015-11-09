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

    public function ownNumberVerificationUrl($key)
    {
        return url(route('phones.verify', ['key' => $key]));
    }

    private function ownNumberVerificationMessage($key)
    {
        return sprintf(
            'If you requested this validation from Pulled Over, please visit %s',
            $this->ownNumberVerificationUrl($key)
        );
    }

    public function verifyOwnNumber(PhoneNumber $number, $key)
    {
        $number->verification_hash = $key;
        $number->save();

        return $this->twilio->text(
            $number->number,
            $this->ownNumberVerificationMessage($key)
        );
    }

    public function friendsNumberVerificationUrl($key)
    {
        return url(route('friends.verify', ['key' => $key]));
    }

    private function friendsNumberVerificationMessage($key, $name)
    {
        return sprintf(
            'Your friend %s wants to add you as a friend on Pulled Over. If you want that too, please visit %s',
            $name,
            $this->friendsNumberVerificationUrl($key)
        );
    }

    public function verifyFriendsNumber(Friend $friend, $key)
    {
        $friend->verification_hash = $key;
        $friend->save();

        return $this->twilio->text(
            $friend->number,
            $this->friendsNumberVerificationMessage($key, $friend->name)
        );
    }

    // @todo: Write methods to handle incoming verificaiton
}
