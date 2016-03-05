<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class OauthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_code_created_if_user_approves()
    {
        //create client
        $client = MockClient::create();

        //create user
        $user = factory(\App\User::class)->create();

        $this->actingAs($user);
        $this->post($client->toTokenUrl(),['approve' => true]);

        //expect redirect with code
        $this->assertNotNull($this->responseQueryCode());
        $this->seeInDatabase('oauth_sessions', ['client_id' => $client->id]);
    }

    protected function responseQueryCode()
    {
        $location = $this->response->headers->get('Location');

        if(!strpos($location, 'code=')){
            return null;
        }

        return explode('code=', $location, 2)[1];
    }
}

class MockClient extends \Illuminate\Support\Fluent
{
    public $tokenRoute = 'oauth.authorize.post';

    protected $attributes = [
        'id' => 1,
        'name' => 'ios',
        'secret' => 'password',
        'redirect_uri' => 'https://foo.bar'
    ];

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public static function create($attributes = [])
    {
        return (new self($attributes))->save();
    }

    /**
     * @return $this
     */
    public function save()
    {
        DB::table('oauth_clients')->insert(array_only($this->attributes,['id','secret','name']));
        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $this->id,
            'redirect_uri' => $this->redirect_uri
        ]);

        return $this;
    }

    /**
     * Get url string to post to for client approval.
     *
     * @return string
     */
    public function toTokenUrl()
    {
        return route($this->tokenRoute,[
            'client_id' => $this->id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
        ]);
    }
}