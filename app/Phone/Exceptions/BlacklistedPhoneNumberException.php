<?php

namespace App\Phone\Exceptions;

use Services_Twilio_RestException;

class BlacklistedPhoneNumberException extends TwilioException
{
    protected static $defaultMessage = 'Sorry, but that phone number has been blacklisted.';
}
