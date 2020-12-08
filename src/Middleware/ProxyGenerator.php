<?php

namespace Reneiw\Hiei\Middleware;

use Closure;

class ProxyGenerator
{
    private array $data;

    /**
     * ProxyGenerator constructor.
     *
     * @param  array  $data Proxy List
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __invoke()
    {
        switch (true) {
            case count($this->data) == 1:
                return current($this->data);
            default:
                return array_shift($this->data);
        }
    }

    public static function factory(array $data, ?self $self = null): Closure
    {
        return function () use ($data, &$self) {
            $self ??= new static($data);
            return $self();
        };
    }
}
