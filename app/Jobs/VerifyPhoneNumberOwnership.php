<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Phone\NumberVerifier;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyPhoneNumberOwnership extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    private $phoneNumber;

    public function __construct($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function handle(NumberVerifier $verifier)
    {
        $verifier->verifyOwnNumber(
            $this->phoneNumber,
            str_random(16)
        );
    }
}
