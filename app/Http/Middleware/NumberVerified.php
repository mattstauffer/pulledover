<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class NumberVerified
{
    /**
     * @var Guard
     */
    private $auth;

    /**
     * VerifiedNumber constructor.
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->auth->user()->phoneNumbers()->verified()->count() === 0){
            return redirect()
                ->route('dashboard')
                ->with('messages', ['You need to verify a phone number before you can add any friends.']);
        }

        return $next($request);
    }
}
