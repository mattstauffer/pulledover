<?php

use App\Phone\TwilioClient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * @group needsInternet
 */
class TwilioClientTest extends TestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new TwilioClient(
            new Services_Twilio(
                env('TWILIO_ACCOUNT_SID'),
                env('TWILIO_ACCESS_TOKEN')
            )
        );
    }

    public function test_it_sends_text_messages()
    {
        $response = $this->client->text(
            '+17346875309',
            'This is a text message.'
        );
        $this->assertEquals('queued', $response->status);
    }

    /**
     * @expectedException App\Phone\Exceptions\InternationalPhoneNumberException
     */
    public function test_it_handles_international_text_messages()
    {
        $response = $this->client->text(
            '+15005550003',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\NonMobilePhoneNumberException
     */
    public function test_it_handles_non_mobile_text_messages()
    {
        $response = $this->client->text(
            '+15005550009',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\InvalidPhoneNumberException
     */
    public function test_it_handles_invalid_text_messages()
    {
        $response = $this->client->text(
            '+15005550001',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\BlacklistedPhoneNumberException
     */
    public function test_it_handles_blacklisted_text_messages()
    {
        $response = $this->client->text(
            '+15005550004',
            'This is a text message.'
        );
    }
}
