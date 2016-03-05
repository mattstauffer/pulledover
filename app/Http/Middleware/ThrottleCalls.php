<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleCalls extends ThrottleRequests
{
    /**
     * Use phone number to create cache key.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        return "twilio.{$request->input('From', 'unknown')}.calls";
    }
}
