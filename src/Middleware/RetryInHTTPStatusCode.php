<?php


namespace Reneiw\Hiei\Middleware;

use Closure;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class RetryInHTTPStatusCode
{
    /**
     * @param $maxTries
     * @param  array|int[]  $codes
     *
     * @return Closure
     */
    public function __invoke(int $maxTries, array $codes = [500, 502, 503, 504]): callable
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) use ($maxTries, $codes) {
            // 超过最大重试次数，不再重试
            if ($retries >= $maxTries) {
                return false;
            }

            // 请求失败，继续重试
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // 如果请求有响应，但是状态码大于等于500，继续重试(这里根据自己的业务而定)
                if (in_array($response->getStatusCode(), $codes)) {
                    return true;
                }
            }
            return false;
        };
    }
}
