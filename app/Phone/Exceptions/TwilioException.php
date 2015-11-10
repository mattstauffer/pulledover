<?php

namespace App\Phone\Exceptions;

use Exception;
use Services_Twilio_RestException;

class TwilioException extends Exception
{
    protected static $defaultMessage = 'Something has gone wrong with our phone service.';

    public static function fromTwilioRestException(Services_Twilio_RestException $e)
    {
        return new static(
            static::$defaultMessage,
            $e->getCode(),
            $e
        );
    }
}
