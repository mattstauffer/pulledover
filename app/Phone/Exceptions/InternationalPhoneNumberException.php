<?php

namespace App\Phone\Exceptions;

use Services_Twilio_RestException;

class InternationalPhoneNumberException extends TwilioException
{
    protected static $defaultMessage = 'Sorry, but we cannot reach that region.';
}
