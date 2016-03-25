<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\VerifyPhoneNumber;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NumbersController extends Controller
{
    public function create()
    {
        return view('numbers.create');
    }

    public function store(Request $request)
    {
        $number = preg_replace('/[^\d]/', '', $request->input('number'));

        $validator = $this->getValidationFactory()->make(compact('number'), [
            'number' => 'required|digits:10|integer|unique_number|globally_unique_number|valid_phone'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $number = Auth::user()->phoneNumbers()->create([
            'number' => $number,
        ]);

        $this->dispatch(new VerifyPhoneNumber($number));

        return redirect()->route('dashboard');
    }
}
