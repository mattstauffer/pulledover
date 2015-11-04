<?php

namespace App\Phone\Exceptions;

use Services_Twilio_RestException;

class InvalidPhoneNumberException extends TwilioException
{
    protected static $defaultMessage = 'Sorry, but that phone number is invalid.';
}
