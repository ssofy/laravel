<?php

namespace SSOfy\Laravel\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use SSOfy\Laravel\Context;

class OAuthClientController extends Controller
{
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /*
     ------------------------------------------------------------
      PUBLIC METHODS
     ------------------------------------------------------------
     */

    public function handleRedirectBack(Request $request)
    {
        /*
         * Validation
         */
        $request->validate([
            'state'             => ['required', 'string', 'min:1'],
            'code'              => ['string', 'min:1'],
            'error'             => ['string'],
            'error_description' => ['string'],
        ], $request->input());

        /*
         * Params
         */
        $code             = $request->input('code');
        $error            = $request->input('error');
        $errorDescription = $request->input('error_description');

        $stateData = $this->context->ssoClient()->handleCallback($request->input());

        if (empty($stateData) && empty($error)) {
            $error            = 'invalid_state';
            $errorDescription = 'Not found or expired state.';
        }

        if (!empty($error)) {
            return view('vendor/ssofy/error', [
                'status'      => $code,
                'title'       => $error,
                'error'       => str_replace('_', ' ', Str::title($error)),
                'description' => $errorDescription,
            ]);
        }

        return redirect($stateData['uri']);
    }

    public function logout(Request $request)
    {
        $redirectUri = filter_var($request->input('redirect_uri', url()->to('/')), FILTER_SANITIZE_URL);
        $everywhere  = boolval($request->input('everywhere', false));

        return $this->context->logout($redirectUri, $everywhere);
    }

    public function socialAuth(Request $request, $provider)
    {
        $redirectUri = filter_var($request->input('redirect_uri', url()->to('/')), FILTER_SANITIZE_URL);

        return $this->context->initiateSocialLogin($provider, $redirectUri);
    }
}
