<?php

namespace App\Phone\Exceptions;

use Services_Twilio_RestException;

class NonMobilePhoneNumberException extends TwilioException
{
    protected static $defaultMessage = 'Sorry, but that is not a mobile phone number.';
}
