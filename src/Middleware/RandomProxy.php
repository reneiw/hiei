<?php


namespace Reneiw\Hiei\Middleware;


use Psr\Http\Message\RequestInterface;

class RandomProxy
{
    /**
     * @param  array  $proxyTable
     *
     * @return callable
     */
    public static function factory(array $proxyTable): callable
    {
        return function (callable $handler) use ($proxyTable) {
            return function (RequestInterface $request, array $options) use ($handler, $proxyTable) {
                self::randomlySelectedProxy($options, $proxyTable, 'http');
                self::randomlySelectedProxy($options, $proxyTable, 'https');
                return $handler($request, $options);
            };
        };
    }

    protected static  function randomlySelectedProxy(array &$options, array $proxyTable, $key = 'http')
    {
        if (array_key_exists($key, $proxyTable) && !empty($proxyTable[$key])) {
            $options['proxy'][$key] = $proxyTable[$key][array_rand($proxyTable[$key], 1)];
        }
    }
}