<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use LucaDegasperi\OAuth2Server\Authorizer;
use LucaDegasperi\OAuth2Server\Facades\Authorizer as AuthorizerFacade;

class OAuthServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias(AuthorizerFacade::class, 'Authorizer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class);
        $this->app->register(\LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class);
    }

    public function provides()
    {
        return [Authorizer::class];
    }

}
