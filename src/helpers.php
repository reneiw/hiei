<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Reneiw\Hiei\HieiMiddleware;
use Reneiw\Hiei\HTTPService;

if (!function_exists('simpleHttp')) {
    function simpleHttp(string $method, string $uri, array $params = null)
    {
        $stack = HandlerStack::create(); // Wrap w/ middleware
        $stack->push(HieiMiddleware::factory());
        $client = new Client(['handler' => $stack]);
        $http = new HTTPService($client);
        return $http->request($method, $uri, $params);
    }
}
