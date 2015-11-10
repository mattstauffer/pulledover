<?php

namespace App\Phone;

use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\Exceptions\InternationalPhoneNumberException;
use App\Phone\Exceptions\InvalidPhoneNumberException;
use App\Phone\Exceptions\NonMobilePhoneNumberException;
use App\Phone\Exceptions\TwilioException;
use Services_Twilio;
use Services_Twilio_RestException;

class TwilioClient
{
    private $fromNumber;
    private $twilio;

    public function __construct($fromNumber, Services_Twilio $twilio)
    {
        $this->fromNumber = $fromNumber;
        $this->twilio = $twilio;
    }

    public static function formatNumberForTwilio($number)
    {
        return '+1' . $number;
    }

    public static function formatNumberFromTwilio($number)
    {
        return str_replace('+1', '', $number);
    }

    public function text($number, $message)
    {
        try {
            return $this->twilio->account->messages->sendMessage(
                $this->fromNumber,
                static::formatNumberForTwilio($number),
                $message
            );
        } catch (Services_Twilio_RestException $e) {
            switch ($e->getCode()) {
                case 21408:
                    throw InternationalPhoneNumberException::fromTwilioRestException($e);
                case 21614:
                    throw NonMobilePhoneNumberException::fromTwilioRestException($e);
                case 21211:
                    throw InvalidPhoneNumberException::fromTwilioRestException($e);
                case 21610:
                    throw BlacklistedPhoneNumberException::fromTwilioRestException($e);
                default:
                    throw TwilioException::fromTwilioRestException($e);
            }
        }
    }
}
