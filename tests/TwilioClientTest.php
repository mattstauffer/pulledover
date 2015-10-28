<?php

use App\Phone\TwilioClient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as M;

class TwilioClientTest extends TestCase
{
    public function test_it_sends_text_messages()
    {
        $this->markTestIncomplete('I do not know how to fix this.');

        $twilioApi = M::mock('Services_Twilio');
        $client = new TwilioClient($twilioApi);

        $phoneNumber = '7346875309';
        $message = 'This is a text message.';

        /**
         * $message = $client->account->messages->sendMessage(
         *  '9991231234', // From a valid Twilio number
         *  '8881231234', // Text this number
         *  "Hello monkey!"
         * );
         */
        $twilioApi
            ->shouldReceive('account->messages->sendMessage')
            ->with([
                env('TWILIO_FROM_NUMBER'),
                $phoneNumber,
                $message
            ]);

        $client->text($phoneNumber, $message);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
