<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwilioRequest;

class IncomingTextController extends Controller
{
    protected $actions = [
        "addToBlacklist" => [
            "STOP",
            "STOPALL",
            "UNSUBSCRIBE",
            "CANCEL",
            "END",
            "QUIT",
        ],
        "removeFromBlacklist" => [
            "START",
            "YES",
            "UNSTOP"
        ]
    ];

    /**
     * Webhook endpoint for incoming text messages from twilio.
     */
    public function receiveText(TwilioRequest $request)
    {
        switch ($this->getActionName($request)) {
            case 'addToBlacklist':
                return $request->phoneNumber()->addToBlacklist();
            case 'removeFromBlacklist':
                return $request->phoneNumber()->removeFromBlacklist();
            default:
                return $this->responseMessage('Unrecognized command: '.$request->Body);
        }
    }

    /**
     * Get the name of the action that should be performed based on the request attributes.
     *
     * @param  TwilioRequest
     * @return string|false
     */
    public function getActionName(TwilioRequest $request)
    {
        $body = strtoupper($request->get('Body'));
        
        return collect($this->actions)->search(function ($keywords) use ($body) {
            return in_array($body, $keywords);
        });
    }

    /**
     * Return message as a twiml xml response.
     *
     * @param  string $message The body of the reply
     * @return Illuminate\Http\Response
     */
    protected function responseMessage($message)
    {
        return response("<Response><Message>{$message}</Message></Response>", 200, [
            "Content-Type" => "text/xml"
        ]);
    }
}
