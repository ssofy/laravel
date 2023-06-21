<?php

namespace SSOfy\Laravel\Listeners;

use SSOfy\Laravel\Context;
use SSOfy\Laravel\Events\TokenDeleted;

class TokenDeleteListener
{
    /**
     * @var Context
     */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function handle(TokenDeleted $event)
    {
        $this->context->apiClient()->invalidateTokenCache($event->token);
    }
}
