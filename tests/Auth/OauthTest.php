<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class OauthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_code_created_only_if_user_approves()
    {
        //create client
        $client = MockClient::create();

        //create user
        $user = factory(\App\User::class)->create();

        $this->actingAs($user);

        //should not create code
        $this->postApprove($client, false);
        $this->assertNull($this->responseQueryCode());
        $this->dontSeeInDatabase('oauth_sessions', ['client_id' => $client->id]);

        //should create code
        $this->postApprove($client);
        $this->assertNotNull($this->responseQueryCode(), 'Failed asserting that redirect query "code" was not null');
        $this->seeInDatabase('oauth_sessions', ['client_id' => $client->id]);
    }

    public function test_code_can_be_exchanged_for_auth_token()
    {
        //create client
        $client = MockClient::create();

        //create user
        $user = factory(\App\User::class)->create();

        $this->actingAs($user);
        $this->postApprove($client);
        $this->postAccessToken($client, $this->responseQueryCode());
        $this->seeJsonStructure(['access_token', 'expires_in', 'token_type']);
    }

    /**
     * Create code for user.
     *
     * @param MockClient $client
     * @param bool $approve
     */
    protected function postApprove(MockClient $client, $approve = true)
    {
        $this->post(
            route('oauth.authorize.post', $client->clientApproveParams()),
            $approve ? ['approve' => true] : ['deny' => true]
        );
    }

    /**
     * Exchange code for access token.
     *
     * @param MockClient $client
     * @param $code
     */
    protected function postAccessToken(MockClient $client, $code)
    {
        $params = array_merge(
            $client->clientAuthParams(),
            [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'response_type' => 'code',
            ]
        );

        $this->post(
            route('oauth.access_token', array_except($params, 'client_secret')),
            $params
        );
    }

    protected function responseQueryCode()
    {
        $location = $this->response->headers->get('Location');

        if (!strpos($location, 'code=')) {
            return null;
        }

        return explode('code=', $location, 2)[1];
    }
}

class MockClient extends \Illuminate\Support\Fluent
{
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
        DB::table('oauth_clients')->insert(array_only($this->attributes, ['id', 'secret', 'name']));
        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $this->id,
            'redirect_uri' => $this->redirect_uri
        ]);

        return $this;
    }

    /**
     * Get params for client approval request.
     *
     * @return string
     */
    public function clientApproveParams()
    {
        return [
            'client_id' => $this->id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
        ];
    }

    /**
     * Get params for code exchange request.
     *
     * @return array
     */
    public function clientAuthParams()
    {
        return [
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirect_uri,
        ];
    }
}
