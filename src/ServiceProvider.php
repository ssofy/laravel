<?php

namespace SSOfy\Laravel;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelAuthServiceProvider;
use SSOfy\Laravel\Events\TokenDeleted;
use SSOfy\Laravel\Listeners\TokenDeleteListener;
use SSOfy\Laravel\Middleware\SSOMiddleware;

class ServiceProvider extends LaravelAuthServiceProvider
{
    public function register()
    {
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $this->app->configure('ssofy');
        }
    }

    public function boot()
    {
        $this->registerPublishes();

        $this->registerPolicies();

        $this->setupBindings();

        $this->setupListeners();

        $this->registerAuthProvider();

        $this->registerMiddleware();

        $this->registerRoutes();
    }

    protected function registerPublishes()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // config
        $this->publishes([
            __DIR__ . '/../config/ssofy.php' => config_path('ssofy.php'),
        ], ['ssofy', 'ssofy:config']);
    }

    private function setupBindings()
    {
        $this->app->singleton(Context::class);

        $this->app->bind('ssofy', Context::class);
    }

    private function setupListeners()
    {
        /** @var \Illuminate\Contracts\Events\Dispatcher::class $events */
        $events = app(\Illuminate\Contracts\Events\Dispatcher::class);

        $events->listen(TokenDeleted::class, TokenDeleteListener::class);
    }

    private function registerAuthProvider()
    {
        $auth = $this->app['auth'];

        $auth->provider('ssofy', function ($app, $config) {
            return $app->make(UserProvider::class, ['providerConfig' => $config]);
        });

        $auth->extend('ssofy', function ($app, $name, $config) use (&$auth) {
            $provider = $auth->createUserProvider($config['provider']);
            return $app->make(ServiceGuard::class, ['provider' => $provider]);
        });
    }

    private function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('ssofy', SSOMiddleware::class);
    }

    private function registerRoutes()
    {
        $routeFile = __DIR__ . '/../routes/ssofy.php';

        $this->app['router']->prefix('/')->group($routeFile);
    }
}
