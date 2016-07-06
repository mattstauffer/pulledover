<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwilioRequest as Request;

class IncomingTextController extends Controller
{
    protected $commands = [
        "unsubscribe" => [
            "STOP",
            "STOPAll",
            "UNSUBSCRIBE",
            "CANCEL",
            "END",
            "QUIT",
        ],
        "subscribe" => [
            "START",
            "YES",
        ]
    ];

    /**
     * Starting point for all incoming text messages.
     */
    public function receiveText(Request $request)
    {
        if ($command = $this->translateCommand($request->Body)) {
            return $this->{$command}($request);
        }

        return $this->responseMessage('Unrecognized command: '.$request->Body);
    }

    protected function subscribe(Request $request)
    {
        $request->phoneNumber()->markBlacklisted(false);
    }

    protected function unsubscribe(Request $request)
    {
        $request->phoneNumber()->markBlacklisted();
    }

    /**
     * Transalate an incoming text to a command using $this->commands.
     *
     * @param  string $body The body of the incoming message
     * @return string|false
     */
    protected function translateCommand($body)
    {
        $body = strtoupper($body);

        foreach ($this->commands as $command => $translations) {
            if (in_array($body, $translations)) {
                return $command;
            }
        }

        return false;
    }

    /**
     * Return message as a twiml xml response.
     *
     * @param  string $message The body of the reply
     * @return Illuminate\Http\Response
     */
    protected function responseMessage($message)
    {
        return new \Illuminate\Http\Response("<Response><Message>{$message}</Message></Response>", 200, [
            "Content-Type" => "text/xml"
        ]);
    }
}
