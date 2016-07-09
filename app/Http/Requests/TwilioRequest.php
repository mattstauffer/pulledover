<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PhoneNumber;
use App\Friend;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Services_Twilio_RequestValidator;

class TwilioRequest extends Request
{
    /**
     * @var PhoneNumber The model instance retrieved using the From number.
     */
    private $phoneNumber = null;

    /**
     * Validate that the incoming request is from twilio.
     *
     * @return bool
     */
    public function authorize(Services_Twilio_RequestValidator $validator)
    {
        return app()->isLocal() || $validator->validate(
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
        if (is_null($this->phoneNumber)) {
            //set the number to first [PhoneNumber | Friend | NULL]
            $this->phoneNumber = PhoneNumber::byNumber($this->input('From'))->first()
                ?: Friend::byNumber($this->input('From'))->first()
                ?: false;
        }

        return $this->phoneNumber;
    }

    public function isFromVerifiedNumber()
    {
        return $this->phoneNumber() && $this->phoneNumber()->is_verified;
    }
}
