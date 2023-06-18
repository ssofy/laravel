<?php

/*
|--------------------------------------------------------------------------
| OAuth2Client Routes
|--------------------------------------------------------------------------
*/
$router->group([
    'namespace'  => 'SSOfy\Laravel\Controllers',
    'prefix'     => '/sso/',
    'middleware' => ['web']
], function () use ($router) {
    $router->get('/callback', 'OAuthClientController@handleRedirectBack');
    $router->get('/logout', 'OAuthClientController@logout')->name('sso.logout');
    $router->get('/social/{provider}', 'OAuthClientController@socialAuth')->name('sso.social');
});
