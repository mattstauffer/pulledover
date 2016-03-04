<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Authorizer;
use View;

class OAuthController extends Controller
{
    /**
     * @var Authorizer
     */
    private $authorizer;

    /**
     * OAuthController constructor.
     *
     * @param Authorizer $authorizer
     */
    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Get form for user to allow/deny the oauth client access.
     *
     * @return mixed
     */
    public function getAuthorize()
    {
        $authParams = $this->authorizer->getAuthCodeRequestParams();
        $formParams = array_except($authParams,'client');
        $formParams['client_id'] = $authParams['client']->getId();

        //get scope ids
        $formParams['scope'] = implode(
            config('oauth2.scope_delimiter'),
            array_map(function ($scope) {
                return $scope->getId();
            }, $authParams['scopes'])
        );

        return view('oauth.authorize.post', [
            'params' => $formParams,
            'client' => $authParams['client']
        ]);
    }

    /**
     * Handle oauth form submission.
     *
     * @param Request $request
     * @param Guard $auth
     *
     * @return mixed
     */
    public function postAuthorize(Request $request, Guard $auth)
    {
        $params = $this->authorizer->getAuthCodeRequestParams();
        $params['user_id'] = $auth->user()->id;
        $redirectUri = '/';

        // If the user has allowed the client to access its data, redirect back to the client with an auth code.
        if ($request->has('approve')) {
            $redirectUri = $this->authorizer->issueAuthCode('user', $params['user_id'], $params);
        }

        // If the user has denied the client to access its data, redirect back to the client with an error message.
        if ($request->has('deny')) {
            $redirectUri = $this->authorizer->authCodeRequestDeniedRedirectUri();
        }

        return redirect($redirectUri);
    }

    /**
     * Fake client response.
     * todo delete this its just for testing
     *
     * @param Request $request
     *
     * @return View
     */
    public function getAccessToken(Request $request)
    {
        $code = $request->get('code');

        return view('oauth.authorize.token',compact('code'));
    }

    /**
     * Exchange code generated in postAuthorize for access token.
     *
     * @return string json access token data
     */
    public function postAccessToken()
    {
        return response()->json($this->authorizer->issueAccessToken());
    }
}