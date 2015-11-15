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

        $this->client = app(TwilioClient::class);
    }

    public function test_it_sends_text_messages()
    {
        $response = $this->client->text(
            '7346875309',
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
            '5005550003',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\NonMobilePhoneNumberException
     */
    public function test_it_handles_non_mobile_text_messages()
    {
        $response = $this->client->text(
            '5005550009',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\InvalidPhoneNumberException
     */
    public function test_it_handles_invalid_text_messages()
    {
        $response = $this->client->text(
            '5005550001',
            'This is a text message.'
        );
    }

    /**
     * @expectedException App\Phone\Exceptions\BlacklistedPhoneNumberException
     */
    public function test_it_handles_blacklisted_text_messages()
    {
        $response = $this->client->text(
            '5005550004',
            'This is a text message.'
        );
    }

    // "Resource not accessible with Test Account Credentials" BOO.
    /*
    public function test_it_marks_invalid_numbers_invalid()
    {
        $this->assertFalse($this->client->validatePhone('1000000001'));
    }

    public function test_it_marks_valid_numbers_valid()
    {
        $this->assertTrue($this->client->validatePhone('3135155151'));
    }
    */
}
