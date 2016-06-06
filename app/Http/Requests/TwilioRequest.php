<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PhoneNumber;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TwilioRequest extends Request
{
    /**
     * Validate that the incoming request is from twilio.
     *
     * @return bool
     */
    public function authorize(\Services_Twilio_RequestValidator $validator)
    {
        return $validator->validate(
            $this->headers->get('X-Twilio-Signature'),
            $this->fullUrl(),
            $this->input()
        );
    }

    public function rules()
    {
        return [];
    }

    public function phoneNumber()
    {
        return PhoneNumber::byNumber($this->input('From'))->first();
    }

    public function isFromVerifiedNumber()
    {
        if ($number = $this->phoneNumber()) {
            return $number->is_verified;
        }

        return false;
    }
}
