<?php

namespace SSOfy\Laravel\Facades;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Facade;
use SSOfy\APIConfig;
use SSOfy\OAuth2Client;
use SSOfy\OAuth2Config;

/**
 * @method static Redirector|RedirectResponse initiateAuthorization($redirectUri = null)
 * @method static Redirector|RedirectResponse initiateAuthorizationWithToken($token = null, $nextUri = null)
 * @method static Redirector|RedirectResponse initiateSocialLogin($provider, $nextUri = null)
 * @method static Redirector|RedirectResponse initiateRegistration($nextUri = null)
 * @method static Redirector|RedirectResponse profilePage()
 * @method static Redirector|RedirectResponse logout($redirectUri = null, $everywhere = false)
 * @method static void logoutWithoutRedirect()
 * @method static OAuth2Client apiClient($config = null)
 * @method static OAuth2Client ssoClient($config = null)
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
