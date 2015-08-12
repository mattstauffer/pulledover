<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class VerifyPhoneNumberOwnership extends Job implements SelfHandling
{
    // use InteractsWithQueue, SerializesModels;

    private $phoneNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $slug = generate string()
        // Insert generator entry for validation($slug)
        // $verifier->verifyOwnNumber($number, $slug, $this users name);
    }
}
