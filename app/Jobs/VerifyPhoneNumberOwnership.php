<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\NumberVerifier;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyPhoneNumberOwnership extends Job
{
    use InteractsWithQueue, SerializesModels;

    private $phoneNumber;

    public function __construct($phoneNumber)
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
            $this->phoneNumber->markBlacklisted();
        }
    }
}
