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

    public function receiveText(Request $request)
    {
        \Log::info('Incoming Message', $request->all());

        if ($command = $this->translateCommand($request->Body)) {
            return $this->{$command}($request);
        }

        return $this->responseMessage('Unrecognized command: '.$request->Body);
    }

    protected function subscribe(Request $request)
    {
        //whitelist number
    }

    protected function unsubscribe(Request $request)
    {
        //blacklist number
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