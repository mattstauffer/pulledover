<?php

namespace App\Http\Middleware;

use Closure;
use Services_Twilio_RequestValidator;

class VerifyTwilioSignature
{
    /**
     * Validator Instance
     *
     * @var \Services_Twilio_RequestValidator
     */
    protected $validator;

    /**
     * TwilioSignatureValidation constructor.
     */
    public function __construct(Services_Twilio_RequestValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Determine if the current request is signed by Twilio
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = $request->header('X-Twilio-Signature');
        $url = $request->getUri();
        $postParameters = $request->input();

        return ! $this->validator->validate($signature, $url, $postParameters)
                        ? response('Unauthorized.', 401)
                        : $next($request);
    }
}
