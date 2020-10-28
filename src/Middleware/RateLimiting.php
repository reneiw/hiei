<?php


namespace Reneiw\Hiei\Middleware;


use Psr\Http\Message\RequestInterface;

class RateLimiting
{
    public function __invoke(callable $handler, int $sleep): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($request, $options);
        };
    }
}