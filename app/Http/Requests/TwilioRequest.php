<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PhoneNumber;

class TwilioRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //todo validate request. see https://twilio-php.readthedocs.org/en/latest/usage/validation.html
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Find phone number by request "Caller".
     *
     * @return PhoneNumber
     */
    public function phoneNumber()
    {
        //todo test correct number returned
        return PhoneNumber::findByTwilioNumber($this->input('Caller'));
    }
}
