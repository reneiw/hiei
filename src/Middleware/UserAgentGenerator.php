<?php

namespace Reneiw\Hiei\Middleware;

use Campo\UserAgent;
use Closure;
use Exception;

class UserAgentGenerator
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function __invoke(): string
    {
        return UserAgent::random($this->data);
    }

    public static function factory(array $data = [], ?self $self = null): Closure
    {
        return function () use ($data, &$self) {
            $self ??= new static($data);
            return $self();
        };
    }
}
