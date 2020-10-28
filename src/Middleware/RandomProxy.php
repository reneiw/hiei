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
    public function __invoke(array $proxyTable): callable
    {
        $self = $this;
        return function (callable $handler) use ($proxyTable, $self) {
            return function (RequestInterface $request, array $options) use ($handler, $proxyTable, $self) {
                $self->randomlySelectedProxy($options, $proxyTable, 'http');
                $self->randomlySelectedProxy($options, $proxyTable, 'https');
                return $handler($request, $options);
            };
        };
    }

    protected function randomlySelectedProxy(array &$options, array $proxyTable, $key = 'http')
    {
        if (array_key_exists($key, $proxyTable) && !empty($proxyTable[$key])) {
            $options['proxy'][$key] = $proxyTable[$key][array_rand($proxyTable[$key], 1)];
        }
    }
}