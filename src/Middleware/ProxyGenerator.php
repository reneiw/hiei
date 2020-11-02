<?php

namespace Reneiw\Hiei\Middleware;

class ProxyGenerator
{
    private array $data;

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

    public static function factory(array $data)
    {
        return function () use ($data) {
            return new static($data);
        };
    }
}
