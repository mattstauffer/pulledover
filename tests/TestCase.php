<?php

use Dotenv\Dotenv;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        if (file_exists(dirname(__DIR__) . '/.env.test')) {
            (new Dotenv(dirname(__DIR__), '.env.test'))->load();
        }

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Disable valid_phone validator.
     * @return $this
     */
    protected function withoutPhoneValidation()
    {
        Validator::extend('valid_phone', function ($attribute, $value, $parameters, $validator) {
            // Skip validation because we can't validate a phone number on test creds
            return true;
        });

        return $this;
    }
}
