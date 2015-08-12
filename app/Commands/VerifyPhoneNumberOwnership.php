<?php

namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhoneNumberOwnership extends Command implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $phoneNumber;

    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        // $slug = generate string()
        // Insert generator entry for validation($slug)
        // $verifier->verifyOwnNumber($number, $slug);
    }
}
