<?php

namespace SSOfy\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use SSOfy\APIClient;
use SSOfy\APIConfig;
use SSOfy\OAuth2Client;
use SSOfy\OAuth2Config;

class Context
{
    const OAUTH2_WORKFLOW_STATE_SESSION_KEY = 'ssofy:oauth:workflow-state';

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string|null $redirectUri
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function initiateAuthorization($redirectUri = null)
    {
        $config = $this->defaultOAuth2Config();

        $uri = $config->getAuthorizeUrl();

        return $this->initAuthCodeFlow($uri, $redirectUri, $config);
    }

    /**
     * @param string|null $token
     * @param string|null $redirectUri
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function initiateAuthorizationWithToken($token = null, $redirectUri = null)
    {
        $config = $this->defaultOAuth2Config();

        $uri = $config->getAuthorizeUrl($token);

        return $this->initAuthCodeFlow($uri, $redirectUri, $config);
    }

    /**
     * @param string $provider
     * @param string|null $redirectUri
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function initiateSocialLogin($provider, $redirectUri = null)
    {
        $config = $this->defaultOAuth2Config();

        $uri = $config->getSocialAuthorizeUrl($provider);

        return $this->initAuthCodeFlow($uri, $redirectUri, $config);
    }

    /**
     * @param string|null $redirectUri
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function register($redirectUri = null)
    {
        $config = $this->defaultOAuth2Config();

        $uri = $config->getRegisterUrl();

        return $this->initAuthCodeFlow($uri, $redirectUri, $config);
    }

    /**
     * @param string|null $redirectUri
     * @param bool $everywhere
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function logout($redirectUri = null, $everywhere = false)
    {
        $this->logoutWithoutRedirect();

        $config = $this->defaultOAuth2Config();

        if (!is_null($redirectUri)) {
            $config->setRedirectUri($redirectUri);
        }

        if ($everywhere) {
            $url = $config->getLogoutEverywhereUrl();
        } else {
            $url = $config->getLogoutUrl();
        }

        return redirect($url);
    }

    /**
     * @return void
     */
    public function logoutWithoutRedirect()
    {
        $ssoClient = $this->ssoClient();

        $sessionState = $this->session->get(self::OAUTH2_WORKFLOW_STATE_SESSION_KEY);
        if (empty($sessionState)) {
            return;
        }

        $ssoClient->deleteState($sessionState);
    }

    public function account()
    {
        return redirect($this->ssoClient()->getAccountUrl());
    }

    /**
     * @param null|APIConfig $config
     * @return APIClient
     */
    public function apiClient($config = null)
    {
        if (is_null($config)) {
            $config = $this->defaultAPIConfig();
        }

        return new APIClient($config);
    }

    /**
     * @param null|OAuth2Config $config
     * @return OAuth2Client
     */
    public function ssoClient($config = null)
    {
        if (is_null($config)) {
            $config = $this->defaultOAuth2Config();
        }

        return new OAuth2Client($config);
    }

    /**
     * @return APIConfig
     */
    public function defaultAPIConfig()
    {
        $config = config('ssofy', []);

        return new APIConfig([
            'domain'      => $config['api']['domain'],
            'key'         => $config['api']['key'],
            'secret'      => $config['api']['secret'],
            'secure'      => $config['api']['secure'],
            'cache_store' => !empty($config['cache']['store']) ? app(Storage::class, [
                'driver' => $config['cache']['store']
            ]) : null,
            'cache_ttl'   => $config['cache']['ttl'],
        ]);
    }

    /**
     * @return OAuth2Config
     */
    public function defaultOAuth2Config()
    {
        $config = config('ssofy.oauth2', []);

        $redirectUri = $config['redirect_uri'];
        if (!is_null($redirectUri)
            && !(
                substr($redirectUri, 0, 7) === 'http://'
                || substr($redirectUri, 0, 8) === 'https://'
                || substr($redirectUri, 0, 2) === '//'
            )
        ) {
            $redirectUri = app('url')->to($config['redirect_uri']);
        }

        return new OAuth2Config([
            'url'               => $config['url'],
            'client_id'         => $config['client_id'],
            'client_secret'     => $config['client_secret'],
            'redirect_uri'      => $redirectUri,
            'pkce_verification' => $config['pkce_verification'],
            'pkce_method'       => $config['pkce_method'],
            'timeout'           => $config['timeout'],
            'scopes'            => $config['scopes'],
            'state_store'       => !empty($config['state']['store']) ? app(Storage::class, [
                'driver' => $config['state']['store'],
            ]) : null,
            'state_ttl'         => $config['state']['ttl'],
        ]);
    }

    private function initAuthCodeFlow($uri, $nextUri, $config)
    {
        $state = $this->ssoClient($config)->initAuthCodeFlow($uri, $nextUri);

        $this->session->put(self::OAUTH2_WORKFLOW_STATE_SESSION_KEY, $state['state']);

        return redirect($state['uri']);
    }
}
