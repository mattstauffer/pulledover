<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\NumberVerifier;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\PhoneNumber;
use Illuminate\Queue\SerializesModels;

class VerifyPhoneNumberOwnership extends Job
{
    use SerializesModels;

    private $phoneNumber;

    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function handle(NumberVerifier $verifier)
    {
        try {
            $verifier->verifyOwnNumber(
                $this->phoneNumber,
                str_random(16)
            );
        } catch (BlacklistedPhoneNumberException $e) {
            $this->phoneNumber->addToBlacklist();
        }
    }
}
