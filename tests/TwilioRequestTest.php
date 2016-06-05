<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TwilioRequestTest extends TestCase
{
    /** @test */
    public function it_should_not_authorize_request_with_invalid_signature()
    {
        $validator = new Services_Twilio_RequestValidator('bad-token');
        $this->post('receive-text', [], [
            'X-Twilio-Signature' => $validator->computeSignature(url('receive-text'), [])
        ]);
        $this->assertResponseStatus(403);
        $this->assertEquals('Forbidden', $this->response->getContent());
    }

    /** @test */
    public function it_should_authorize_request_with_valid_signature()
    {
        $validator = new Services_Twilio_RequestValidator(env('TWILIO_ACCESS_TOKEN'));
        $this->post('receive-text', [], [
            'X-Twilio-Signature' => $validator->computeSignature(url('receive-text'), [])
        ]);

        $this->assertResponseOk();
    }
}
