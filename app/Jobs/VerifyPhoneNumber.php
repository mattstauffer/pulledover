<?php

namespace App\Jobs;

use App\Friend;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\NumberVerifier;
use App\PhoneNumber;
use App\Events\FriendWasBlacklisted;
use App\Events\PhoneNumberWasBlacklisted;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhoneNumber extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var PhoneNumber|Friend
     */
    private $receiver;

    public function __construct($receiver)
    {
        $this->receiver = $receiver;
    }

    public function handle(NumberVerifier $verifier)
    {
        try {
            $this->sendVerificationText($verifier);
        } catch (BlacklistedPhoneNumberException $e) {
            $this->receiver->markBlacklisted();
        }
    }

    protected function sendVerificationText(NumberVerifier $verifier)
    {
        $key = str_random(16);

        switch (get_class($this->receiver)) {
            case Friend::class: {
                $verifier->verifyFriendsNumber($this->receiver, $key);
                break;
            }
            case PhoneNumber::class: {
                $verifier->verifyOwnNumber($this->receiver, $key);
                break;
            }
            default: {
                throw new \Exception('Unrecognized Receiver type');
            }
        }
    }
}
