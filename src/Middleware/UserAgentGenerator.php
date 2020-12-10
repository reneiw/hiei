<?php

namespace Reneiw\Hiei\Middleware;

use Campo\UserAgent;
use Exception;

class UserAgentGenerator
{
    public function __construct()
    {
    }

    /**
     * @return string
     * @throws Exception
     */
    public function __invoke(): string
    {
        return UserAgent::random();
    }
}
