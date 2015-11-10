<?php

namespace App\Phone;

use App\Friend;
use App\PhoneNumber;
use App\Phone\TwilioClient;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;

class NumberVerifier
{
    private $twilio;
    private $urlGenerator;
    private $auth;

    public function __construct(TwilioClient $twilio, UrlGenerator $urlGenerator, AuthManager $auth)
    {
        $this->twilio = $twilio;
        $this->urlGenerator = $urlGenerator;
        $this->auth = $auth;
    }

    private function ownNumberVerificationUrl($key)
    {
        return $this->urlGenerator->route('phones.verify', ['key' => $key]);
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

    private function friendsNumberVerificationUrl($key)
    {
        return $this->urlGenerator->route('friends.verify', ['key' => $key]);
    }

    private function friendsNumberVerificationMessage($key)
    {
        return sprintf(
            'Your friend %s wants to add you as a friend on Pulled Over. If you want that too, please visit %s',
            $this->auth->user()->name,
            $this->friendsNumberVerificationUrl($key)
        );
    }

    public function verifyFriendsNumber(Friend $friend, $key)
    {
        $friend->verification_hash = $key;
        $friend->save();

        return $this->twilio->text(
            $friend->number,
            $this->friendsNumberVerificationMessage($key)
        );
    }
}
