<?php

namespace App\Jobs;

use App\Exceptions\UnrecognizedReceiverTypeException;
use App\Friend;
use App\Jobs\Job;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\NumberVerifier;
use App\PhoneNumber;
use App\ReceivesTextMessages as Receiver;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhoneNumber extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * Create a new job instance.
     *
     * @param Receiver $receiver
     */
    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     *
     * @param NumberVerifier $verifier
     *
     * @throws UnrecognizedReceiverTypeException
     */
    public function handle(NumberVerifier $verifier)
    {
        try {
            $this->sendVerificationText($verifier);
        } catch (BlacklistedPhoneNumberException $e) {
            //todo handle blacklisted number
        }
    }

    /**
     * @param NumberVerifier $verifier
     *
     * @throws UnrecognizedReceiverTypeException
     */
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
                throw new UnrecognizedReceiverTypeException;
            }
        }
    }
}
