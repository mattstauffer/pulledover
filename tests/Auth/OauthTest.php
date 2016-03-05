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
        $this->post($client->toCodeUrl(),['approve' => true]);

        //expect redirect with code
        $this->assertNotNull($this->responseQueryCode());
        $this->seeInDatabase('oauth_sessions', ['client_id' => $client->id]);
    }

    public function test_code_can_be_exchanged_for_auth_token()
    {
        //create client
        $client = MockClient::create();

        //create user
        $user = factory(\App\User::class)->create();

        $this->actingAs($user);
        $this->post($client->toCodeUrl(),['approve' => true]);

        $params = array_merge(
            $client->clientAuthParams(),
            [
                'code' => $this->responseQueryCode(),
                'grant_type' => 'authorization_code',
                'response_type' => 'code',
            ]
        );

        $this->post(route('oauth.access_token', array_except($params,'client_secret')), $params);

        $this->seeJsonStructure(['access_token','expires_in','token_type']);
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
    public $codeRoute = 'oauth.authorize.post';
    public $tokenRoute = 'oauth.access_token';

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
    public function toCodeUrl()
    {
        return route($this->codeRoute,[
            'client_id' => $this->id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
        ]);
    }

    public function clientAuthParams()
    {
        return [
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirect_uri,
        ];
    }
}