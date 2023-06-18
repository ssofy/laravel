<?php

namespace SSOfy\Laravel\Facades;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Facade;
use SSOfy\APIConfig;
use SSOfy\OAuth2Client;
use SSOfy\OAuth2Config;

/**
 * @method static Redirector|RedirectResponse initiateAuthorization()
 * @method static Redirector|RedirectResponse initiateAuthorizationWithToken()
 * @method static Redirector|RedirectResponse initiateSocialLogin()
 * @method static Redirector|RedirectResponse account()
 * @method static Redirector|RedirectResponse register()
 * @method static Redirector|RedirectResponse logout()
 * @method static void logoutWithoutRedirect()
 * @method static OAuth2Client apiClient()
 * @method static OAuth2Client ssoClient()
 * @method static APIConfig defaultAPIConfig()
 * @method static OAuth2Config defaultOAuth2Config()
 */
class SSOfy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ssofy';
    }
}
