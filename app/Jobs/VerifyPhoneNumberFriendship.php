<?php

namespace App\Jobs;

use App\Friend;
use App\Jobs\Job;
use App\Phone\NumberVerifier;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use Illuminate\Queue\SerializesModels;

class VerifyPhoneNumberFriendship extends Job
{
    use SerializesModels;

    private $friend;

    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }

    public function handle(NumberVerifier $verifier)
    {
        try {
            $verifier->verifyFriendsNumber(
                $this->friend,
                str_random(16)
            );
        } catch (BlacklistedPhoneNumberException $e) {
            $this->friend->addToBlacklist();
        }
    }
}
